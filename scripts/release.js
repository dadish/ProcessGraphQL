const execa = require("execa");
const path = require("path");
const {
  extraneousFiles,
  vendorExtraneousFiles,
  execute,
  updateFile,
} = require("./commons");

async function release(releaseLevel) {
  // remove all php vendor code except the required for production
  await execute("rm", ["-rf", "vendor"], "Remove dev dependant vendor files");
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
  await execute("git", ["add", "."], "Add changes to git stage");

  // commit whatever on git stage
  await execute(
    "git",
    ["commit", "-m", `chore(release): [skip ci] v${releaseLevel}`],
    "Commit changes to the release branch"
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
