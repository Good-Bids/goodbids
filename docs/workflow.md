# Git Workflow

- Create your new branch off of `main`
- For a branch for a single issue, use the following `{yourInitials}/{issueNumber}-{issueDescription}`
- If you plan to have multiple issues per branch, you can drop the issue Number.
Examples:
  - `ns/32-ipsum-dolor`
  - `bd/154-ipsum-dolor-adipiscing`
  - `jf/ipsum-dolor-adipiscing`
- Complete the task and push up the branch
- Deploy your commits to the `staging` environment so that PMs and Devs can test your changes.
- Create a PR with `main` as the base branch
- At least 2 reviewers will review code and approve PR
- Once a PR is approved and passes QA, it can be merged into `main`.


## Commits
All commits should be tagged with a issue number `[#129] Lorem ipsum dolor sit amet`.

This makes it easy to follow commits back to a issue. If there is not a issue associated with the commit, you can leave off the issue number like - `Lorem ipsum dolor sit amet`
