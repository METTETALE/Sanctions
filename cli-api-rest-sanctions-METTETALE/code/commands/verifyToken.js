import fs from "fs";

function getTokenFromFile() {
  try {
    const token = fs.readFileSync("token.txt", "utf-8");
    return token;
  } catch (error) {
    return null;
  }
}

export default getTokenFromFile;
