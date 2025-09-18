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

  // Cek kalau user sudah ada
  if (fs.existsSync(userFile)) {
    return res.status(400).json({ message: "User already exists" });
  }

  // Encode password pakai Base64
  const encodedPw = Buffer.from(password).toString("base64");

  // Simpan ke file
  fs.mkdirSync(path.dirname(userFile), { recursive: true });
  fs.writeFileSync(userFile, JSON.stringify({ password: encodedPw }, null, 2));

  return res.status(200).json({ message: "Success Register" });
}
