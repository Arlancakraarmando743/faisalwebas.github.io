export default async function handler(req, res) {
  if (req.method !== "POST") {
    return res.status(405).json({ message: "Method not allowed" });
  }

  const { username, password } = req.body;
  if (!username || !password) {
    return res.status(400).json({ message: "Username and password required" });
  }

  const repoOwner = process.env.VERCEL_GIT_REPO_OWNER;
  const repoName = process.env.VERCEL_GIT_REPO_SLUG;
  const filePath = `users/${username}.json`;

  const response = await fetch(
    `https://api.github.com/repos/${repoOwner}/${repoName}/contents/${filePath}`,
    {
      headers: {
        "Authorization": `token ${process.env.GITHUB_TOKEN}`,
        "Accept": "application/vnd.github.v3.raw",
      },
    }
  );

  if (response.status === 404) {
    return res.status(400).json({ message: "User not found" });
  }

  const data = await response.json();
  const decodedPw = Buffer.from(data.password, "base64").toString("utf8");

  if (decodedPw !== password) {
    return res.status(401).json({ message: "Invalid credentials" });
  }

  return res.status(200).json({ message: "Success Login" });
}
