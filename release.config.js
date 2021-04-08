module.exports = {
  branches: [{ name: "main", prerelease: "rc" }, { name: "stable" }],
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
    [
      "@semantic-release/npm",
      {
        npmPublish: false,
      },
    ],
    [
      "@semantic-release/exec",
      {
        prepareCmd: "node scripts/release.js ${nextRelease.version}",
      },
    ],
    [
      "@semantic-release/git",
      {
        assets: ["package.json", "ProcessGraphQL.module", "Changelog.md"],
      },
    ],
    [
      "@semantic-release/github",
      {
        assets: [
          { path: "ProcessGraphQL.zip", label: "ProcessWire Module (zip)" },
        ],
      },
    ],
  ],
};
