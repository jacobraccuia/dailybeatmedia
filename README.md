# DAILY BEAT MEDIA

DB-NETWORK: a global plugin that handles cron and shared theme elements
- keep all global functions in db-network



note: perhaps a better theme structure would be a bare bones parent theme with children?

9/8/16 - I've finally taken the adivce above.
DB Network is a global "theme".  It controls the header, the soundcloud player, the footer, and all shared resources.  If it's used on more than one site, it needs to be located there.

Daily Beat v3.0 is the main website, it exists solely for daily-beat.com
Channel is the theme used for every other website.  Each site should have it's own child theme that has it's own customizations per request.  They use the same Channel theme as a cookie cutter with color customizations. 

Maybe the customizations should be done via an admin panel.  I don't care about that yet though.