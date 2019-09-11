#! /usr/bin/env node
const path = require("path");
const shell = require("shelljs");
const ora = require("ora");

const silent = { silent: true };
const RELEASE_BRANCH_NAME = "release";
const MASTER_BRANCH_NAME = "master";

const RELEASE_TEST = "test";
let spinner;

const releaseLevel = process.argv[2];
if (!releaseLevel) {
  shell.echo(
    "Error: Should provide a version argument. See `npm help version`."
  );
  shell.exit(1);
}

// create the release branch
spinner = ora(`Creating ${RELEASE_BRANCH_NAME} branch`).start();
const releaseBranchCreate = shell.exec(
  `git branch ${RELEASE_BRANCH_NAME}`,
  silent
);
if (releaseBranchCreate.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(releaseBranchCreate.stderr || releaseBranchCreate.stdout);
  shell.exit(1);
}

// checkout the release branch
spinner = ora(`Switching to the ${RELEASE_BRANCH_NAME} branch`).start();
const releaseBranchCheckout = shell.exec(
  `git checkout ${RELEASE_BRANCH_NAME}`,
  silent
);
if (releaseBranchCheckout.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(releaseBranchCheckout.stderr || releaseBranchCheckout.stdout);
  shell.exit(1);
}

// remove the unwanted files
spinner = ora("Removing extraneous files").start();
const removeDirs = shell.rm(
  "-rf",
  [
    "bin",
    "imgs",
    "test",
    "vendor",
    ".travis.yml",
    "Changelog.md",
    "composer.lock",
    "package-lock.json",
    "ScreenCast.md",
    "Todo.md",
    ".gitignore",
    "GraphiQL/package.json",
    "GraphiQL/package-lock.json"
  ],
  silent
);
if (removeDirs.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(removeDirs.stderr || removeDirs.stdout);
  shell.exit(1);
}

// install vendor dependencies
spinner = ora("Installing php dependencies").start();
const vendorInstall = shell.exec("composer install --no-dev", silent);
if (vendorInstall.code === 0) {
  shell.echo("Installed vendor dependencies");
  spinner.succeed();
} else {
  shell.echo("Error: Could not install vendor dependencies.");
  spinner.fail(vendorInstall.stderr);
  shell.exit(1);
}

// remove vendor extraneous files
spinner = ora("Removinf extraneous files from vendor code").start();
const vendorRemoveFiles = shell.rm("-rf", [
  "vendor/webonyx/graphql-php/docs",
  "vendor/webonyx/graphql-php/examples",
  "vendor/webonyx/graphql-php/.scrutinizer.yml",
  "vendor/webonyx/graphql-php/CHANGELOG.md",
  "vendor/webonyx/graphql-php/composer.json",
  "vendor/webonyx/graphql-php/LICENSE",
  "vendor/webonyx/graphql-php/phpcs.xml.dist",
  "vendor/webonyx/graphql-php/phpstan.neon.dist",
  "vendor/webonyx/graphql-php/README.md",
  "vendor/webonyx/graphql-php/UPGRADE.md"
]);
if (vendorRemoveFiles.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(vendorRemoveFiles.stderr || vendorRemoveFiles.stdout);
  console.log(vendorRemoveFiles.stderr);
  shell.exit(1);
}

// extract graphiql library
spinner = ora("Extracting the graphiql dependencies from node_modules").start();
const extractGraphiQL = shell.cp(
  path.resolve(`${__dirname}/../node_modules/graphiql/graphiql.min.js`),
  path.resolve(`${__dirname}/../node_modules/graphiql/graphiql.css`),
  path.resolve(`${__dirname}/../GraphiQL/`)
);
if (extractGraphiQL.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(extractGraphiQL.stderr || extractGraphiQL.stdout);
  shell.exit(1);
}

// add all changes to stage
spinner = ora("Git adding the changes").start();
const stageChanges = shell.exec("git add . && git reset node_modules", silent);
if (stageChanges.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(stageChanges.stderr || stageChanges.stdout);
  shell.exit(1);
}

// commit all changes
spinner = ora("Committing the changes").start();
const commitChanges = shell.exec(
  "git commit -m 'Remove extraneous files for release.'",
  silent
);
if (commitChanges.code === 0) {
  spinner.succeed();
} else {
  spinner.fail(commitChanges.stderr || commitChanges.stdout);
  shell.exit(1);
}

if (releaseLevel === RELEASE_TEST) {
  // snapshot the release in "test" branch
  const releaseSnapshotting = shell.exec(`git branch ${RELEASE_TEST}`);
  if (releaseSnapshotting.code === 0) {
    shell.echo("Snapshot the release.");
  } else {
    shell.echo("Error: Could not snapshot the release.");
    console.log(releaseSnapshotting.stderr);
    shell.exit(1);
  }
} else {
  // tag the release
  const releaseTagging = shell.exec(`npm version ${releaseLevel}`);
  if (releaseTagging.code === 0) {
    shell.echo("Tag the release.");
  } else {
    shell.echo("Error: Could not tag the release.");
    console.log(releaseTagging.stderr);
    shell.exit(1);
  }
}

// checkout the master branch
const masterCheckout = shell.exec(`git checkout ${MASTER_BRANCH_NAME}`, silent);
if (masterCheckout.code === 0) {
  shell.echo(`Checked out the ${MASTER_BRANCH_NAME} branch`);
} else {
  shell.echo(`Error: Could not checkout the ${MASTER_BRANCH_NAME} branch.`);
  console.log(masterCheckout.stderr || masterCheckout.stdout);
  shell.exit(1);
}

// delete the release branch
const releaseBranchDelete = shell.exec(
  `git branch -D ${RELEASE_BRANCH_NAME}`,
  silent
);
if (releaseBranchDelete.code === 0) {
  shell.echo(`Deleted ${RELEASE_BRANCH_NAME} branch.`);
} else {
  shell.echo(`Error: Could not delete ${RELEASE_BRANCH_NAME} branch.`);
  console.log(releaseBranchDelete.stderr || releaseBranchDelete.stdout);
  shell.exit(1);
}

// install vendor deps back
const vendorInstallAll = shell.exec("composer install", silent);
if (vendorInstallAll.code === 0) {
  shell.echo("Installed vendor deps back.");
} else {
  shell.echo(
    'Warning: Could not install vendor deps back. Try "composer install" to fix it.'
  );
  console.log(vendorInstallAll.stderrr);
  shell.exit(1);
}

// if it was a test release then do nothing on master branch
if (releaseLevel === RELEASE_TEST) {
  return;
}

// increment version in package.json file
// for master branch, since changes in release
// branch do not affect master branch, the package
// version in package.json file in master branch is old
const incementPackageVersion = shell.exec(
  `npm version ${releaseLevel} --no-git-tag-version`,
  silent
);
if (incementPackageVersion.code === 0) {
  shell.echo(`Incremented package version on master branch`);
} else {
  shell.echo(
    `Error: Could not increment package version on ${MASTER_BRANCH_NAME} branch.`
  );
  console.log(incementPackageVersion.stderr);
  shell.exit(1);
}

// commit package version change on master branch
const packageVersionCommit = shell.exec(
  `git commit --all -m "${incementPackageVersion}"`,
  silent
);
if (packageVersionCommit.code === 0) {
  shell.echo(
    `Committed package version update on ${MASTER_BRANCH_NAME} branch.`
  );
} else {
  shell.echo(
    `Error: Could not commit package version update on ${MASTER_BRANCH_NAME} branch.`
  );
  console.log(packageVersionCommit.stderr);
  shell.exit(1);
}
