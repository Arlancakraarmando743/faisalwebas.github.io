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

  // Encode password ke base64
  const encodedPw = Buffer.from(password).toString("base64");
  const userFilePath = `users/${username}.json`;

  try {
    // Cek apakah file user sudah ada
    const checkResp = await fetch(
      `https://api.github.com/repos/${owner}/${repo}/contents/${userFilePath}`,
      {
        headers: { Authorization: `token ${token}` },
      }
    );

    if (checkResp.ok) {
      return res.status(400).json({ error: "User already exists" });
    }

    // Commit file baru ke GitHub
    const content = Buffer.from(
      JSON.stringify({ password: encodedPw }, null, 2)
    ).toString("base64");

    const resp = await fetch(
      `https://api.github.com/repos/${owner}/${repo}/contents/${userFilePath}`,
      {
        method: "PUT",
        headers: {
          Authorization: `token ${token}`,
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          message: `Add user ${username}`,
          content,
        }),
      }
    );

    if (!resp.ok) {
      const err = await resp.json();
      return res.status(500).json({ error: "Failed to save user", detail: err });
    }

    return res.status(200).json({ success: true, message: "User registered!" });
  } catch (e) {
    return res.status(500).json({ error: e.message });
  }
}
