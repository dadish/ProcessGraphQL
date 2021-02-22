module.exports = {
  branches: ["main", { name: "dev", prerelease: "rc" }],
  plugins: [
    [
      "@semantic-release/commit-analyzer",
      {
        releaseRules: [{ type: "build", release: "patch" }],
      },
    ],
    "@semantic-release/release-notes-generator",
    [
      "@semantic-release/exec",
      {
        prepareCmd: "node scripts/release.js ${nextRelease.version}",
      },
    ],
    "@semantic-release/github",
    [
      "@semantic-release/git",
      {
        assets: ["package.json", "ProcessGraphQL.module"],
        message:
          "chore(release): [skip ci] ${nextRelease.version}\n\n${nextRelease.notes}",
      },
    ],
  ],
};
