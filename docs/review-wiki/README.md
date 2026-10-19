# 📚 Review Wiki

Decisions made in response to pull request review feedback — what a reviewer or
Copilot raised, what we decided, and why.

It exists so a decision gets made once. Before triaging new review feedback, the
AI searches here; while writing code, it reads here. A rule recorded in this wiki
is a house rule, not an archive entry.

Two kinds of entry:

- **[patterns/](patterns/)** — rules, conventions, and idioms that recur across
  the codebase. Most entries live here.
- **[files/](files/)** — decisions specific to one file's implementation,
  mirroring the source tree. `app/Services/BillingService.php` is recorded at
  `files/app/Services/BillingService.md`.

Every entry links to the entries related to it, in both directions. Follow the
links rather than searching twice.

<!-- BEGIN GENERATED ENTRIES -->

## 🧩 Patterns

Cross-cutting rules and conventions.

*No entries yet.*

## 📄 Files

Decisions specific to one file.

*No entries yet.*
<!-- END GENERATED ENTRIES -->

## 🔍 Searching

The listing above is generated. To search the entries themselves:

```bash
rg -il "transaction" docs/review-wiki/            # by topic
rg -l "BillingService.php" docs/review-wiki/      # every entry touching a file
rg -l "verdict: wont-fix" docs/review-wiki/       # everything we declined
```

## ✏️ Editing

**Edit this wiki freely.** These are ordinary markdown files in the repository,
they go through pull request review like anything else, and a correction you make
is treated as authoritative — the AI extends hand-edited entries, it does not
overwrite them.

The one exception is the block between the `BEGIN GENERATED ENTRIES` and
`END GENERATED ENTRIES` markers above. That listing is rebuilt from the entries'
front matter and your changes to it will be overwritten:

```bash
php vendor/insight-llc/developer-settings/resources/boost/skills/address-review-comments/bin/build-entries.php
```

Everything else on this page — including this prose — is yours to edit; the
generator never touches it.

Worth correcting when you see it:

- **Reasoning that is wrong or has gone stale.** Rewrite it in place and add a
  line under *History*. Git holds the previous version; the entry should read as
  current truth.
- **A verdict you disagree with.** Change `verdict:` in the front matter and say
  why. The next triage pass will surface your version.
- **A rule filed under `files/` that should be a pattern.** Move it to
  `patterns/`, list the affected files in `files:`, and update the links.
- **A broken or one-way link.** Links are load-bearing here; a link the search
  cannot follow is a decision that gets made twice.

Because the listing is generated, front matter is the only authoritative place
for an entry's title, verdict, tags, and date — correct it there, then rebuild in
the same commit.

## 🧱 Entry shape

Front matter makes an entry findable by file, tag, and verdict:

```yaml
---
title: Do not validate inside a database transaction
verdict: fix | wont-fix | deferred | invalid
tags: [transactions, validation]
files:
  - app/Services/Billing/BillingService.php
sources:
  - repo#21042 (copilot)
updated: 2026-07-29
---
```

The body carries the comment, the decision, the reasoning, how to apply it, and
where it has come up. Prose, not notes — a reader who was not in the review
should be able to act on it.

Verdicts:

| Verdict | Meaning |
|---------|---------|
| `fix` | The comment was right; the code changed. |
| `wont-fix` | Understood and deliberately declined. |
| `deferred` | Legitimate, out of scope, follow-up issue filed. |
| `invalid` | The reviewer misread the code. |

`wont-fix` entries earn their keep the fastest — they are what stops an automated
reviewer raising the same point on every future pull request.

That is not aspirational: Copilot and CodeRabbit are both configured to read this
wiki before commenting, via
`.github/instructions/review-decisions.instructions.md` (distributed by
`insight-llc/developer-settings`) and CodeRabbit's own configuration.
An entry you write here reduces future review noise; an entry you leave stale
misleads the reviewers as well as the humans.

## 🚫 Where this must not live

Not in the guidance `insight-llc/developer-settings` ships. That guidance
reaches every Insight repository, and these decisions are specific to this
codebase — shipping them would push this project's file paths into every other
Insight repository.
