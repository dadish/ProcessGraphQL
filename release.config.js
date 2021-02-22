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
      "@semantic-release/changelog",
      {
        changelogFile: "Changelog.md",
      },
    ],
    "@semantic-release/npm",
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
        assets: ["package.json", "ProcessGraphQL.module", "Changelog.md"],
        message:
          "chore(releasing): [skip ci] ${nextRelease.version}\n\n${nextRelease.notes}",
      },
    ],
  ],
};
