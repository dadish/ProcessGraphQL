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
      "@semantic-release/git",
      {
        assets: [
          "graphiql",
          "src",
          "templates",
          "composer.json",
          "LICENSE",
          "package.json",
          "ProcessGraphQL.module",
          "ProcessGraphQLConfig.php",
          "Readme.md",
        ],
      },
    ],
    [
      "@semantic-release/exec",
      {
        publishCmd: "node scripts/release.js ${nextRelease.version}",
      },
    ],
    "@semantic-release/github",
  ],
};
