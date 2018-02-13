#! /usr/bin/env node
const shell = require('shelljs')

const silent = { silent: true }
const releaseBranchName = 'release'
const masterBranchName = 'ci'

// create the release branch
const releaseBranchCreate = shell.exec(`git branch ${releaseBranchName}`, silent)
if (releaseBranchCreate.code === 0) {
	shell.echo(`Created ${releaseBranchName} branch`)
} else {
	shell.echo(`Error: Could not create ${releaseBranchName} branch.`)
	console.log(releaseBranchCreate.stderr || releaseBranchCreate.stdout)
	shell.exit(1)
}

// checkout the release branch
const releaseBranchCheckout = shell.exec(`git checkout ${releaseBranchName}`, silent)
if (releaseBranchCheckout.code === 0) {
	shell.echo(`Checked out the ${releaseBranchName} branch.`)
} else {
	shell.echo(`Error: Could not checkout the ${releaseBranchName} branch.`)
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
	'package.json',
	'ScreenCast.md',
	'Todo.md',
	'.gitignore',
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

// checkout the master branch
const masterCheckout = shell.exec(`git checkout ${masterBranchName}`, silent)
if (masterCheckout.code === 0) {
	shell.echo(`Checked out the ${masterBranchName} branch`)
} else {
	shell.echo(`Error: Could not checkout the ${masterBranchName} branch.`)
	console.log(masterCheckout.stderr || masterCheckout.stdout)
	shell.exit(1)
}

// delete the release branch
// const releaseBranchDelete = shell.exec(`git branch -D ${releaseBranchName}`, silent)
// if (releaseBranchDelete.code === 0) {
// 	shell.echo(`Deleted ${releaseBranchName} branch.`)
// } else {
// 	shell.echo(`Error: Could not delete ${releaseBranchName} branch.`)
// 	console.log(releaseBranchDelete.stderr || releaseBranchDelete.stdout)
// 	shell.exit(1)
// }
