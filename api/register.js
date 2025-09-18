export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ message: "Username and password required" });
  }

  const encodedPw = Buffer.from(password).toString("base64");
  const content = JSON.stringify({ password: encodedPw }, null, 2);
  const b64Content = Buffer.from(content).toString("base64");

  const repoOwner = process.env.VERCEL_GIT_REPO_OWNER;
  const repoName = process.env.VERCEL_GIT_REPO_SLUG;
  const filePath = `users/${username}.json`;

  const response = await fetch(
    `https://api.github.com/repos/${repoOwner}/${repoName}/contents/${filePath}`,
    {
      method: "PUT",
      headers: {
        "Authorization": `token ${process.env.GITHUB_TOKEN}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        message: `Add user ${username}`,
        content: b64Content,
      }),
    }
  );

  const result = await response.json();
  if (!response.ok) {
    return res.status(500).json({ message: "GitHub API error", error: result });
  }

  return res.status(200).json({ message: "Success Register" });
}
