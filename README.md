# Digital Footprint Profile

*v1.0.0*

A tool used to recommend you what to consider deleting from your social media profiles.

### Supported Networks

- Twitter (Tweets)
- Facebook (Text only posts) (Sadly people use this)

### Planned Network Support

- Tumblr (Posts)
- Twitter (DMs, likes)
- Facebook (Messages, Shares, Likes, All posts)

### Integrations

- Sendgrid
- Mustache templates

### Scoring

- Keywords
- Sentiment dictionary classification (Thanks to [jwhennessey/phpinsight](https://github.com/jwhennessey/phpinsight))

### Demo

You can use my publicly available instance [here](https://dfp.zacharydubois.moe).

![DFP-Demo-1.png](https://raw.githubusercontent.com/ZacharyDuBois/Digital-Footprint-Profile/master/Images/DFP-Demo-1.png)
![DFP-Demo-2.png](https://raw.githubusercontent.com/ZacharyDuBois/Digital-Footprint-Profile/master/Images/DFP-Demo-2.png)
![DFP-Demo-3.png](https://raw.githubusercontent.com/ZacharyDuBois/Digital-Footprint-Profile/master/Images/DFP-Demo-3.png)
![DFP-Demo-4.png](https://raw.githubusercontent.com/ZacharyDuBois/Digital-Footprint-Profile/master/Images/DFP-Demo-4.png)

### Side Notes

- This was made voluntarily for my school at request of the PBIS team. Not to
mention my on going hate for the way people use social media.
- I demoed this in front of the board of education on 2016-01-06.
- This provides an option for the user to send an email about what they should
remove. This feature is hard coded and cannot be removed or turned off easily.
- This currently does not scan comments due to the API complexity of doing so.

### Privacy Statement/Acceptable Use

This web application is used to scan the end user's social media accounts
for explicit or bad content based on rules. All flagged posts/tweets/etc
will be saved in the session file and can be exported by the application
administrator. This application *should not be used to sell, distribute,
illegal purposes, and/or used for personal gain.* All data pulled from
connected OAuth providers is rendered to be anonymous. Names in
content/posts/tweets is currently not redacted. The use of these names is
any way is not permitted under the use of this application unless found to
be endangering. The end user is only authorizing the use of their data to
find content that is recommended to delete for a better online appearance.
This application may only read from connected OAuth providers and can only
view networks that the end user of the application has authorized it to do
so. The OAuth tokens/keys are stored in a temporary session. Upon expiry,
these tokens are no longer retrievable. Information provided is personally
identifiable and should be handled with care and caution. The end user's
email, if provided, may only be used to email them a link to their session
to review their posts/accounts from home. Please review
`/dfp/theme/privacy.mustache` and make necessary changes for your setup
before deploying/using.


TL;DR: Only content the gets flagged by any of the rules is saved.
Account information is redacted but remains personally identifiable.
Tokens to access your account are only saved temporarily. This data
may be exported by the administrator of the application for
statistical purposes only. Also make sure to edit `/dfp/theme/privacy.mustache`.

### License Statement

This project is licensed under a MIT license. But due to the nature of
the project's purpose. Please read the `Privacy Statement/Acceptable Use`
to ensure this project is not abused.
