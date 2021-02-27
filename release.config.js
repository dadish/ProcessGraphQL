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
    [
      "@semantic-release/npm",
      {
        npmPublish: false,
      },
    ],
    [
      "@semantic-release/exec",
      {
        verifyConditionsCmd: "node scripts/command.js verifyConditionsCmd",
        analyzeCommitsCmd: "node scripts/command.js analyzeCommitsCmd",
        verifyReleaseCmd: "node scripts/command.js verifyReleaseCmd",
        generateNotesCmd: "node scripts/command.js generateNotesCmd",
        prepareCmd: "node scripts/command.js prepareCmd",
        addChannelCmd: "node scripts/command.js addChannelCmd",
        publishCmd: "node scripts/command.js publishCmd",
        successCmd: "node scripts/command.js successCmd",
        failCmd: "node scripts/command.js failCmd",
      },
    ],
    [
      "@semantic-release/git",
      {
        assets: ["package.json", "ProcessGraphQL.module", "Changelog.md"],
        message:
          "chore(releasing): [skip ci] ${nextRelease.version}\n\n${nextRelease.notes}",
      },
    ],
    "@semantic-release/github",
  ],
};
