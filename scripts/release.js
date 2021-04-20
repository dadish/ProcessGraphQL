const fs = require("fs");
const process = require("process");
const archiver = require("archiver");
const path = require("path");
const {
  releaseDirectories,
  releaseFiles,
  execute,
  updateFile,
} = require("./commons");

const tarballReleaseFiles = () =>
  new Promise((resolve, reject) => {
    // create a file to stream archive data to.
    const zipFilename = `${process.cwd()}/ProcessGraphQL.zip`;
    const output = fs.createWriteStream(zipFilename);
    const archive = archiver("zip", {
      zlib: { level: 9 }, // Sets the compression level.
    });

    // This event is fired when the data source is drained no matter what was the data source.
    // It is not part of this library but rather from the NodeJS Stream API.
    // @see: https://nodejs.org/api/stream.html#stream_event_end
    output.on("end", function () {
      resolve();
    });

    // good practice to catch warnings (ie stat failures and other non-blocking errors)
    archive.on("warning", function (err) {
      reject(err);
    });

    // good practice to catch this error explicitly
    archive.on("error", function (err) {
      reject(err);
    });

    // pipe archive data to the file
    archive.pipe(output);

    // add all the directories
    releaseDirectories.forEach((dirname) => {
      const source = `${process.cwd()}/${dirname}`;
      archive.directory(source, dirname);
    });

    // add all the files
    releaseFiles.forEach((name) => {
      const source = `${process.cwd()}/${name}`;
      archive.file(source, { name });
    });

    // finalize the archive (ie we are done appending files but streams have to finish yet)
    // 'close', 'end' or 'finish' may be fired right after calling this method so register to them beforehand
    archive.finalize();
  });

async function release(releaseLevel) {
  // update the .module file version number.
  await updateFile(
    path.resolve(`${__dirname}/../ProcessGraphQL.module`),
    /\'version\' => \'\d+\.\d+\.\d+(-rc\.\d+)?\'/,
    `'version' => '${releaseLevel}'`,
    "Update version in ProcessGraphQL.module file."
  );

  await tarballReleaseFiles();
}

const releaseLevel = process.argv[2];
if (!releaseLevel) {
  console.error(
    "Error: Should provide a version argument. See `npm help version`."
  );
  process.exit(1);
}

try {
  release(releaseLevel);
} catch (err) {
  console.error(err);
}
