module.exports = {
  branches: ["main", { name: "dev", prerelease: "rc" }],
  plugins: [
    [
      "@semantic-release/commit-analyzer",
      "@semantic-release/release-notes-generator",
      {
        releaseRules: [{ type: "build", release: "patch" }],
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
    "@semantic-release/github",
  ],
};
