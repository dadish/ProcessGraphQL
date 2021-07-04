const execa = require("execa");
const fs = require("fs-extra");

const releaseDirectories = [
  "graphiql",
  "src",
  "templates",
  "vendor/webonyx/graphql-php/src",
];

const releaseFiles = [
  "vendor/webonyx/graphql-php/composer.json",
  "vendor/webonyx/graphql-php/LICENSE",
  "vendor/webonyx/graphql-php/README.md",
  "vendor/autoload.php",
  "Changelog.md",
  "composer.json",
  "LICENSE",
  "ProcessGraphQL.module",
  "ProcessGraphQLConfig.php",
  "Readme.md",
];

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
  releaseDirectories,
  releaseFiles,
  updateFile,
  execute,
};
