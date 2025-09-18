import fs from "fs";
import path from "path";

export default function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ message: "Username and password required" });
  }

  const userFile = path.join(process.cwd(), "data", `${username}.json`);
  if (!fs.existsSync(userFile)) {
    return res.status(400).json({ message: "User not found" });
  }

  const userData = JSON.parse(fs.readFileSync(userFile, "utf8"));
  const decodedPw = Buffer.from(userData.password, "base64").toString("utf8");

  if (decodedPw !== password) {
    return res.status(401).json({ message: "Invalid credentials" });
  }

  return res.status(200).json({ message: "Success Login" });
}
