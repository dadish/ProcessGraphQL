import { execa } from "execa";
import * as fs from "fs-extra";

export const releaseDirectories = [
  "graphiql",
  "src",
  "templates",
  "vendor/webonyx/graphql-php/src",
  "vendor/composer",
];

export const releaseFiles = [
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

export async function updateFile(filename, matcher, replaceStr, message) {
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

export async function execute(file, args, message) {
  let result = {};
  try {
    console.log(`🟡 ${message}`);
    result = await execa(file, args);
    console.log(result.stdout);
    console.log(`✅ ${message}`);
  } catch (err) {
    console.log("🔴 error", err);
    throw new Error(err);
  }
  return result;
}
