import fs from "fs";
import path from "path";

export default function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  const filePath = path.join(process.cwd(), "users.json");
  if (!fs.existsSync(filePath)) {
    return res.status(400).json({ message: "No users registered yet" });
  }

  const users = JSON.parse(fs.readFileSync(filePath, "utf8"));
  const { username, password } = req.body;

  const user = users.find(u => u.username === username && u.password === password);

  if (!user) {
    return res.status(401).json({ message: "Invalid credentials" });
  }

  return res.status(200).json({ message: "Success Login" });
}
