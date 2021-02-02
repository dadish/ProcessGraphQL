const path = require("path");
const {
  extraneousFiles,
  vendorExtraneousFiles,
  execute,
  updateFile,
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

  // remove all php vendor code except the required for production
  await execute("rm", ["-rf", "vendor"], "Removing extraneous files");
  await execute(
    "composer",
    ["install", "--no-dev"],
    "Installing php dependencies (--no-dev)"
  );

  // remove the rest of the files that we don't need in the production code
  await execute("rm", ["-rf", ...extraneousFiles], "Removing extraneous files");

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

  // commit whatever on git stage
  await execute("git", [
    "commit",
    "-m",
    "Remove extraneous files for release.",
  ]);

  // version tag
  await execute("git", ["tag", `v${releaseLevel}`], "Tagging the release.");

  // switch back to main
  await execute("git", ["checkout", "main"], "Switching back to main branch.");

  // delete the release branch
  await execute(
    "git",
    ["branch", "-D", "release"],
    "Deleting the release branch"
  );

  // install all deps back
  await execute("npm", ["install"], "Install all vendor deps back.");

  // update package.json version
  await execute(
    "npm",
    ["version", releaseLevel, "--no-git-tag-version"],
    "Incrementing the package version on the main branch"
  );

  // update the .module file version number on main branch
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
    "Committing package version update on main branch."
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
