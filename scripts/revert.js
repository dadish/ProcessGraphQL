const { execute } = require("./commons");

async function revert() {
  // reset the last commit
  await execute("git", ["reset", "HEAD^"], "Reset the last commit");

  // remove all the changes
  await execute("git", ["checkout", "."], "Remove all the changes");
}

try {
  revert();
} catch (err) {
  console.error(err);
}
