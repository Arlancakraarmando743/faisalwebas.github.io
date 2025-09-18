export default async function handler(req, res) {
  const token = process.env.GITHUB_TOKEN;
  const owner = process.env.VERCEL_GIT_REPO_OWNER;
  const repo = process.env.VERCEL_GIT_REPO_SLUG;

  const resp = await fetch(
    `https://api.github.com/repos/${owner}/${repo}/contents/uploads`,
    { headers: { Authorization: `token ${token}` } }
  );

  if (!resp.ok) return res.status(500).json({ error: "Gagal ambil uploads" });

  const files = await resp.json();
  const urls = files.map(f => f.download_url);

  res.status(200).json({ files: urls });
}
