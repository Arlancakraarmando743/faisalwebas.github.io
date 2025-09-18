import formidable from "formidable";
import fs from "fs";

export const config = {
  api: { bodyParser: false }
};

export default async function handler(req, res) {
  if (req.method !== "POST") return res.status(405).end();

  const form = new formidable.IncomingForm();
  form.parse(req, async (err, fields, files) => {
    if (err) return res.status(500).json({ error: "Form parse error" });

    const username = fields.username;
    const file = files.file[0];

    const fileContent = fs.readFileSync(file.filepath);
    const base64 = fileContent.toString("base64");

    const token = process.env.GITHUB_TOKEN;
    const owner = process.env.VERCEL_GIT_REPO_OWNER;
    const repo = process.env.VERCEL_GIT_REPO_SLUG;
    const path = `uploads/${username}-${file.originalFilename}`;

    const resp = await fetch(
      `https://api.github.com/repos/${owner}/${repo}/contents/${path}`,
      {
        method: "PUT",
        headers: {
          Authorization: `token ${token}`,
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          message: `Upload by ${username}`,
          content: base64
        })
      }
    );

    if (!resp.ok) {
      const error = await resp.json();
      return res.status(500).json({ error });
    }

    res.status(200).json({ success: true });
  });
}
