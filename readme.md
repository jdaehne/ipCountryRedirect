# ipCountryRedirect
IP-Address based Country Redirect. The Extra uses the IP-API-Service of IPLocate to get the country-code of the user and redirects to the defined context.


# Usage
So far 1,500 daily requests are free. If you need more request you can get yourself an API-Key at [APILocate](https://www.iplocate.io/) and set it in the System-Settings:
## System Settings
| setting | default | description |
| --- | --- | --- |
| ipcr.apikey |  | Optional API-Key. Get your Key at [APILocate](https://www.iplocate.io/) |
| ipcr.anonymizeip | false | Anonymize IP-Address (example: 203.0.113.195 -> 203.0.113.0) |

Now just define the country-codes in the context you want the user to be redirected to:
## Context Settings
| setting | default | description |
| --- | --- | --- |
| ipcr.countries |  | County-Codes: Comma separatet list of county-codes that match this context. Example: de,at,ch |

## Snippet
Place the Snippet into the Root-Context of your site:
```
[[!ipCountryRedirect]]
```

# Example
If you have 3 Context:
- web = Root-Context
- de = Germany
- us = USA

### 1. Step
Place the Snippet `[[!ipCountryRedirect]]` into your Root-Context (web) into your site_start.

### 2. Step
Define all your country-codes in your contexts with the context-key `ipcr.countries`. Example:
- de = de,at,ch
- us = us

Finished! Your user should be redirected to your defined contexts.

### Fallback
By default the user gets redirectet to your Default-Context (System-Setting: `default_context`).
If the Extra can't locate the user or the country is not defined in any context: The extra will redirect to your default context.
Make shure your Default-Context is not the same context as your Root-Context.
