module.exports = {
  branches: ["main", { name: "dev", prerelease: "rc" }],
  plugins: [
    [
      "@semantic-release/commit-analyzer",
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
  ],
};
