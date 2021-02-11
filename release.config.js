module.exports = {
  branches: ["release", { name: "beta", prerelease: "beta" }],
  plugins: [
    [
      "@semantic-release/commit-analyzer",
      {
        parseOpts: { headerPattern: "*" },
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
