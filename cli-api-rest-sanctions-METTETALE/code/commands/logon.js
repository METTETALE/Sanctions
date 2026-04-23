const BaseUrl = "http://localhost:4500/api/auth/login";

import fs from "fs";

async function logonCommand(email, password) {
  try {
    let res = await fetch(BaseUrl, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ email, password }),
    });
    let data = await res.json();
    if (!res.ok) {
      console.error(`Error: ${res.status} - ${data.message}`);
      return;
    }
    console.log("Token crée avec succes.");
    fs.writeFileSync("token.txt", data.token, (err) => {
      if (err) {
        console.error("Error writing token to file:", err.message);
        return;
      }
    });
    console.log("Token ecrit dans token.txt");
  } catch (error) {
    console.error("Error during logon:", error.message);
  }
}

export default logonCommand;
