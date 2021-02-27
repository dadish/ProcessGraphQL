const command = process.argv[2];
if (!command) {
  console.error(
    "Error: Should provide a version argument. See `npm help version`."
  );
  process.exit(1);
}

console.log("✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅");
console.log(command);
console.log("✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅✅");
