const execa = require("execa");
const fs = require("fs-extra");
const ora = require("ora");

const extraneousFiles = [
  "bin",
  "imgs",
  "test",
  'node_modules',
  "coverage",
  "scripts",
  ".travis.yml",
  "Changelog.md",
  "composer.lock",
  "composer.json",
  "package.json",
  "package-lock.json",
  "ScreenCast.md",
  "Todo.md",
  ".gitignore",
  "GraphiQL/package.json",
  "GraphiQL/package-lock.json"
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
  "vendor/webonyx/graphql-php/UPGRADE.md"
];

const fakeSpinner = {
  start: () => {},
  succeed: () => {},
  fail: () => {}
};

async function updateFile(filename, matcher, replaceStr, message) {
  const spinner = message ? ora(message) : fakeSpinner;
  try {
    spinner.start();
    let content = await fs.readFile(filename, "utf8");
    content = content.replace(matcher, replaceStr);
    await fs.writeFile(filename, content);
    spinner.succeed();
  } catch (err) {
    spinner.fail();
    throw new Error(err);
  }
}

async function execute(file, arguments, message) {
  const spinner = message ? ora(message) : fakeSpinner;
  try {
    spinner.start();
    await execa(file, arguments);
    spinner.succeed();
  } catch (err) {
    spinner.fail();
    throw new Error(err);
  }
}

module.exports = {
  extraneousFiles,
  vendorExtraneousFiles,
  updateFile,
  execute
};
