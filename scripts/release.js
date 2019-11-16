const path = require("path");
const {
  extraneousFiles,
  vendorExtraneousFiles,
  execute,
  updateFile
} = require("./commons");

async function release(releaseLevel) {
  // create the release branch
  await execute("git", ["branch", "release"], "Creating the release branch");

  // switch to the release branch
  await execute(
    "git",
    ["checkout", "release"],
    "Switching to the release branch"
  );

  // remove files that we don't need in the production code
  await execute("rm", ["-rf", ...extraneousFiles], "Removing extraneous files");

  // we removed all the vendor code in the previous step
  // now we install it again, but only the production dependencies.
  await execute(
    "composer",
    ["install", "--no-dev"],
    "Installing php dependencies (--no-dev)"
  );

  // remove verdor's files that we don't use in production code
  await execute(
    "rm",
    ["-rf", ...vendorExtraneousFiles],
    "Removing extraneous files from graphql-php"
  );

  // update the .module file version number.
  await updateFile(
    path.resolve(`${__dirname}/../ProcessGraphQL.module`),
    /\'version\' => \'\d+\.\d+\.\d+(-rc\d+)?\'/,
    `'version' => '${releaseLevel}'`,
    "Update version in ProcessGraphQL.module file."
  );

  // add changes to git stage
  await execute("git", ["add", "."], "");

  // remove node_modules from git stage
  await execute("git", ["reset", "node_modules"], "");

  // commit whatever on git stage
  await execute("git", [
    "commit",
    "-m",
    "Remove extraneous files for release."
  ]);

  // version tag
  await execute("npm", ["version", releaseLevel], "Tagging the release.");

  // switch back to master
  await execute(
    "git",
    ["checkout", "master"],
    "Switching back to master branch."
  );

  // delete the master branch
  await execute(
    "git",
    ["branch", "-D", "release"],
    "Deleting the release branch"
  );

  // install all deps back
  await execute("composer", ["install"], "Install all vendor deps back.");

  // update package.json version
  await execute(
    "npm",
    ["version", releaseLevel, "--no-git-tag-version"],
    "Incrementing the package version on the master branch"
  );

  // update the .module file version number on master branch
  await updateFile(
    path.resolve(__dirname + "/../ProcessGraphQL.module"),
    /\'version\' => \'\d+\.\d+\.\d+(-rc\d+)?\'/,
    `'version' => '${releaseLevel}'`,
    "Update version in ProcessGraphQL.module file."
  );

  // commit version update
  await execute(
    "git",
    ["commit", "--all", "-m", releaseLevel],
    "Committing package version update on master branch."
  );
}

const releaseLevel = process.argv[2];
if (!releaseLevel) {
  console.error(
    "Error: Should provide a version argument. See `npm help version`."
  );
  process.exit(1);
}

try {
  release(releaseLevel);
} catch (err) {
  console.error(err);
}
