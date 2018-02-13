#! /usr/bin/env node
const shell = require('shelljs')

const silent = { silent: true }
const RELEASE_BRANCH_NAME = 'release'
const MASTER_BRANCH_NAME = 'ci'

const RELEASE_MAJOR = 'major'
const RELEASE_MINOR = 'minor'
const RELEASE_PATCH = 'patch'

const releaseLevel = process.argv[2]
if (
	releaseLevel !== RELEASE_MAJOR &&
	releaseLevel !== RELEASE_MINOR &&
	releaseLevel !== RELEASE_PATCH
) {
	shell.echo(`Error: The provided release level is incorrect: ${releaseLevel}. Allowed: ${RELEASE_MAJOR}, ${RELEASE_MINOR}, ${RELEASE_PATCH}`)
	shell.exit(1)
}

// create the release branch
const releaseBranchCreate = shell.exec(`git branch ${RELEASE_BRANCH_NAME}`, silent)
if (releaseBranchCreate.code === 0) {
	shell.echo(`Created ${RELEASE_BRANCH_NAME} branch`)
} else {
	shell.echo(`Error: Could not create ${RELEASE_BRANCH_NAME} branch.`)
	console.log(releaseBranchCreate.stderr || releaseBranchCreate.stdout)
	shell.exit(1)
}

// checkout the release branch
const releaseBranchCheckout = shell.exec(`git checkout ${RELEASE_BRANCH_NAME}`, silent)
if (releaseBranchCheckout.code === 0) {
	shell.echo(`Checked out the ${RELEASE_BRANCH_NAME} branch.`)
} else {
	shell.echo(`Error: Could not checkout the ${RELEASE_BRANCH_NAME} branch.`)
	console.log(releaseBranchCheckout.stderr || releaseBranchCheckout.stdout)
	shell.exit(1)
}

// remove the unwanted files
const removeDirs = shell.rm('-rf', [
	'bin',
	'imgs',
	'test',
	'vendor',
	'.travis.yml',
	'Changelog.md',
	'composer.lock',
	'package-lock.json',
	'ScreenCast.md',
	'Todo.md',
	'.gitignore',

	// remove extraneous
	'GraphiQL/src',
	'GraphiQL/.gitignore',
	'GraphiQL/package.json',
	'GraphiQL/README.md',
	'GraphiQL/yarn.lock',
], silent)
if (removeDirs.code === 0) {
	shell.echo('Removed extraneous files.')
} else {
	shell.echo('Error: Could not remove files.')
 shell.exit(1)
	
}

// add all changes to stage
const stageChanges = shell.exec('git add . && git reset node_modules', silent)
if (stageChanges.code === 0) {
	shell.echo('Added changes to stage.')
} else {
	shell.echo(`Error: Could not stage changes.`)
	console.log(stageChanges.stderr || stageChanges.stdout)
	shell.exit(1)
}

// install vendor dependencies
const vendorInstall = shell.exec('composer install --no-dev', silent)
if (vendorInstall.code === 0) {
	shell.echo('Installed vendor dependencies')
} else {
	shell.echo('Error: Could not install vendor dependencies.')
	console.log(vendorInstall.stderr)
	shell.exit(1)
}

// remove vendor extraneous files
const vendorRemoveFiles = shell.rm('-rf', [
	'vendor/youshido/graphql/examples',
	'vendor/youshido/graphql/Tests',
	'vendor/youshido/graphql/.gitignore',
	'vendor/youshido/graphql/.scrutinizer.yml',
	'vendor/youshido/graphql/.travis.yml',
	'vendor/youshido/graphql/CHANGELOG-1.1.md',
	'vendor/youshido/graphql/composer.json',
	'vendor/youshido/graphql/LICENSE',
	'vendor/youshido/graphql/phpunit.xml.dist',
	'vendor/youshido/graphql/README.md',
	'vendor/youshido/graphql/UPGRADE-1.1.md',
])
if (vendorRemoveFiles.code === 0) {
	shell.echo('Removed extraneous vendor files.')
} else {
	shell.echo('Error: Could not remove extraneous vendor files.')
	console.log(vendorRemoveFiles.stderr)
	shell.exit(1)
}

// stage vendor changes
const stageVendorChanges = shell.exec('git add vendor composer.lock')
if (stageVendorChanges.code === 0) {
	shell.echo('Staged vendor changes.')
} else {
	shell.echo('Error: Could not stage vendor changes.')
	console.log(stageVendorChanges.stderr)
	shell.exit(1)
}

// commit all changes
const commitChanges = shell.exec("git commit -m 'Remove extraneous files for release.'", silent)
if (commitChanges.code === 0) {
	shell.echo('Changes committed.')
} else {
	shell.echo('Error: Could not commit changes.')
	console.log(commitChanges.stderr || commitChanges.stdout)
	shell.exit(1)
}

// tag the release
const releaseTagging = shell.exec(`npm version ${releaseLevel}`)
if (releaseTagging.code === 0) {
	shell.echo('Tag the release.')
} else {
	shell.echo('Error: Could not tag the release.')
	console.log(releaseTagging.stderr)
	shell.exit(1)
}

// checkout the master branch
const masterCheckout = shell.exec(`git checkout ${MASTER_BRANCH_NAME}`, silent)
if (masterCheckout.code === 0) {
	shell.echo(`Checked out the ${MASTER_BRANCH_NAME} branch`)
} else {
	shell.echo(`Error: Could not checkout the ${MASTER_BRANCH_NAME} branch.`)
	console.log(masterCheckout.stderr || masterCheckout.stdout)
	shell.exit(1)
}

// delete the release branch
const releaseBranchDelete = shell.exec(`git branch -D ${RELEASE_BRANCH_NAME}`, silent)
if (releaseBranchDelete.code === 0) {
	shell.echo(`Deleted ${RELEASE_BRANCH_NAME} branch.`)
} else {
	shell.echo(`Error: Could not delete ${RELEASE_BRANCH_NAME} branch.`)
	console.log(releaseBranchDelete.stderr || releaseBranchDelete.stdout)
	shell.exit(1)
}

// install vendor deps back
const vendorInstallAll = shell.exec('composer install', silent)
if (vendorInstallAll.code === 0) {
	shell.echo('Installed vendor deps back.')
} else {
	shell.echo('Warning: Could not install vendor deps back. Try "composer install" to fix it.')
	console.log(vendorInstallAll.stderrr)
	shell.exit(1)
}

// increment version in package.json file
// for master branch, since changes in release
// branch do not affect master branch, the package
// version in package.json file in master branch is old
const incementPackageVersion = shell.exec(`npm version ${releaseLevel} --no-git-tag-version`)
if (incementPackageVersion.code === 0) {
	shell.echo(`Incremented package version on master branch`)
} else {
	shell.echo(`Error: Could not increment package version on ${MASTER_BRANCH_NAME} branch.`)
	console.log(incementPackageVersion.stderr)
	shell.exit(1)
}

// commit package version change on master branch
const packageVersionCommit = shell.exec('git commit --all -m "Update package version."')
if (packageVersionCommit.code === 0) {
	shell.echo(`Committed package version update on ${MASTER_BRANCH_NAME} branch.`)
} else {
	shell.echo(`Error: Could not commit package version update on ${MASTER_BRANCH_NAME} branch.`)
	console.log(packageVersionCommit.stderr)
	shell.exit(1)
}