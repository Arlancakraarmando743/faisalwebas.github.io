import fs from "fs";
import path from "path";

export default function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  const filePath = path.join(process.cwd(), "users.json");
  let users = [];

  if (fs.existsSync(filePath)) {
    users = JSON.parse(fs.readFileSync(filePath, "utf8"));
  }

  const { username, password } = req.body;

  if (users.find(u => u.username === username)) {
    return res.status(400).json({ message: "Username already exists" });
  }

  users.push({ username, password });
  fs.writeFileSync(filePath, JSON.stringify(users, null, 2));

  return res.status(200).json({ message: "Success Register" });
}
