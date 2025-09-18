import fetch from "node-fetch";

export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ error: "Only POST allowed" });
  }

  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ error: "Username & password required" });
  }

  const token = process.env.GITHUB_TOKEN;
  const owner = process.env.VERCEL_GIT_REPO_OWNER;
  const repo = process.env.VERCEL_GIT_REPO_SLUG;
  const userFilePath = `users/${username}.json`;

  try {
    const resp = await fetch(
      `https://api.github.com/repos/${owner}/${repo}/contents/${userFilePath}`,
      {
        headers: { Authorization: `token ${token}` },
      }
    );

    if (!resp.ok) {
      return res.status(400).json({ error: "User not found" });
    }

    const data = await resp.json();
    const fileContent = Buffer.from(data.content, "base64").toString("utf-8");
    const userData = JSON.parse(fileContent);

    const encodedPw = Buffer.from(password).toString("base64");
    if (userData.password !== encodedPw) {
      return res.status(401).json({ error: "Invalid password" });
    }

    return res.status(200).json({ success: true, message: "Login success!" });
  } catch (e) {
    return res.status(500).json({ error: e.message });
  }
}
