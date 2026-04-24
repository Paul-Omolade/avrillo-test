
# PROCESS

## How long this took
This took me approximately 1 hours and 40 minutes in total, including project setup, implementation, testing, UI refinement, and checking the SDLT rules against HMRC guidance.

## How I used AI tooling
I used AI tooling to speed up implementation, mainly for:
- drafting the initial Laravel and Vue scaffolding
- proposing a first pass of the SDLT service structure
- suggesting initial test cases
- improving the wording of the user-facing breakdown

One specific example I rejected was an earlier AI suggestion around first-time buyer handling above £500,000. I rewrote that logic after checking HMRC guidance, because first-time buyer relief does not apply when the purchase price exceeds £500,000.

## How I verified the maths
I checked the residential rate bands, first-time buyer relief rules, and additional property surcharge against HMRC’s published SDLT guidance for England. I also verified threshold cases such as £125,000, £250,000, £300,000, and £500,000, along with additional-property examples, to confirm the expected outputs matched the published rules.

## What I’d do with another hour
- Add a clearer inline explanation when first-time buyer and additional property are both selected
- Add more regression tests around upper-band boundary cases
- Refine the UI copy and formatting slightly
- Add support for historical rates based on purchase date if that became part of the requirements
- Add a next-step action after the calculation, such as “Talk to an AI agent” or “Speak to a live agent”, so a user can get help understanding the SDLT result in the context of their quote or transaction
