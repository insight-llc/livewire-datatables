---
applyTo: "**"
---

# Recorded review decisions

This repository keeps a review wiki at `docs/review-wiki/` recording decisions
already made in response to earlier review feedback: what a reviewer raised, what
was decided, and why.

## Required first step: search the wiki

**Do this before writing any review comment.** If `docs/review-wiki/` does not exist
in this repository, skip the rest of this file.

1. **For every file in the diff**, look for entries about it:

   ```bash
   rg -l "PATH/OF/CHANGED/FILE" docs/review-wiki/
   ```

2. **For the topics your comments would raise** — the rule, pattern, or API involved
   — search by keyword:

   ```bash
   rg -il "KEYWORD|OTHER_KEYWORD" docs/review-wiki/
   ```

3. **List what has already been declined**, so you do not raise it again:

   ```bash
   rg -l "verdict: wont-fix" docs/review-wiki/
   ```

4. **Read every entry those searches return** — specifically its `## The decision`
   and `## Why` sections. The reasoning is what tells you whether the situation in
   front of you is genuinely different or the same point in new clothes.

**This search is not optional, and it is not a matter of judgement.** Being free to
disagree with a recorded decision does not license skipping the step that tells you
one exists. Looking is required; agreeing is not.

## Do not review `docs/`

**Skip every file under `docs/` — do not comment on it.**

`docs/review-wiki/` is the record of decisions already made. A comment on an entry
re-opens the argument that entry exists to close, and a review thread is the wrong
place to amend a record — the `## History` section is.

The rest of `docs/` is prose written for people. Judgements about phrasing,
structure, or completeness are opinions the maintainers did not ask for, and they
crowd out the findings that matter.

The wiki search above stays required. This exclusion governs where you leave
comments, not what you read.

## Review `.ai/` like code, except what Boost generates

`.ai/` holds files this repository owns: its own guidelines, such as the pest and
pint overrides, and the rule files it records under `.ai/rules/`. Review them like
any other change.

**Skip `.ai/rules/index.md` and every file under `.ai/rules/boost/` — do not comment
on them.** Laravel Boost generates both. It rewrites the index in full whenever a
rule is recorded, and rewrites `.ai/rules/boost/` on its next run where the project
enables scoped guidelines. A comment on either has nowhere to land.

The shared coding standards do not live in `.ai/`. `insight-llc/developer-settings`
ships them to Laravel Boost, which composes them into the agent files this
repository commits, such as `CLAUDE.md` and `AGENTS.md`. Read those guidelines and
hold the code to them. A change to them belongs in `insight-llc/developer-settings`,
not in this repository.

## Where entries live

- `docs/review-wiki/patterns/` — rules and conventions that recur anywhere.
- `docs/review-wiki/files/` — decisions specific to one file, mirroring the source
  tree (`app/Services/BillingService.php` → `files/app/Services/BillingService.md`).

Front matter makes entries searchable by relevance:

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

## What to do with what you find

Treat a recorded decision as advice from the maintainers — a strong prior, not a gag
order. These entries exist so settled questions are not re-argued from scratch every
pull request, not to suppress a reviewer who has something new to say.

| Verdict | Default response |
|---------|-----------------|
| `fix` | The rule was adopted. Flag code that **violates** it; take the rule itself as settled. |
| `wont-fix` | Deliberately declined. Assume it stays declined. |
| `deferred` | Acknowledged and tracked elsewhere. Treat it as known rather than new. |
| `invalid` | A previous reviewer misread the code. Check whether you are making the same misreading. |

These are defaults, not verdicts on your judgement.

## Disagreeing with a recorded decision

**If you think an entry is wrong, raise it.** Silently withholding a real problem
because a document told you not to mention it is the worse failure — an entry that is
out of date should be corrected, and a reviewer is often the first to notice.

What makes a challenge useful rather than noise:

- **Cite the entry** you are disagreeing with, by path.
- **Say what is new.** The code changed since the entry was written; the reasoning
  rests on a factual error; the entry did not consider this case; there is a
  correctness, security, or data-loss consequence that was not weighed.
- **State it once, substantively.** Make the argument properly and leave it. A
  maintainer may still choose the recorded decision, and that is their call.

What is not useful: restating the original point as though no decision exists,
raising it again on the next pull request after it was reaffirmed, or objecting on
style preference alone where the entry already weighed style.

Entries carry a `## History` section precisely so they can be corrected in place. A
well-argued disagreement is the input to that. Treat the wiki as a record you can
help improve, not a wall.
