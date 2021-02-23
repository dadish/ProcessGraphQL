const execa = require("execa");
const fs = require("fs-extra");

const extraneousFiles = [
  ".github",
  "bin",
  "imgs",
  "test",
  "coverage",
  "scripts",
  ".travis.yml",
  "Changelog.md",
  "composer.lock",
  "composer.json",
  "package-lock.json",
  "ScreenCast.md",
  "Todo.md",
  ".gitignore",
  "GraphiQL/package.json",
  "GraphiQL/package-lock.json",
  "release.config.js",
  ".editorconfig",
  ".prettierrc",
];

const vendorExtraneousFiles = [
  "vendor/webonyx/graphql-php/docs",
  "vendor/webonyx/graphql-php/examples",
  "vendor/webonyx/graphql-php/.scrutinizer.yml",
  "vendor/webonyx/graphql-php/CHANGELOG.md",
  "vendor/webonyx/graphql-php/composer.json",
  "vendor/webonyx/graphql-php/LICENSE",
  "vendor/webonyx/graphql-php/phpcs.xml.dist",
  "vendor/webonyx/graphql-php/phpstan.neon.dist",
  "vendor/webonyx/graphql-php/README.md",
  "vendor/webonyx/graphql-php/UPGRADE.md",
  "vendor/webonyx/graphql-php/.github",
  "vendor/webonyx/graphql-php/.coveralls.yml",
  "vendor/webonyx/graphql-php/phpstan-baseline.neon",
];

const fakeSpinner = {
  start: () => {},
  succeed: () => {},
  fail: () => {},
};

async function updateFile(filename, matcher, replaceStr, message) {
  try {
    console.log(`🟡 ${message}`);
    let content = await fs.readFile(filename, "utf8");
    content = content.replace(matcher, replaceStr);
    await fs.writeFile(filename, content);
    console.log(`✅ ${message}`);
  } catch (err) {
    console.log("🔴 error", err);
    throw new Error(err);
  }
}

async function execute(file, arguments, message) {
  let result = {};
  try {
    console.log(`🟡 ${message}`);
    result = await execa(file, arguments);
    console.log(result.stdout);
    console.log(`✅ ${message}`);
  } catch (err) {
    console.log("🔴 error", err);
    throw new Error(err);
  }
  return result;
}

module.exports = {
  extraneousFiles,
  vendorExtraneousFiles,
  updateFile,
  execute,
};
