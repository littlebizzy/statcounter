# StatCounter

Optimized StatCounter tracking

## Changelog

### 2.0.1
- migrate old options `project_id` and `security_code` to new `statcounter` option array if exist / not exist
- delete old options if options migration occurs

### 2.0.0
- completely refactored code to WordPress standards
- latest version of StatCounter tracking snippet (HTTPS-only now)
- using WP Settings API now and cleaner code
- improved security and performance
- support for PHP 7.0 to PHP 8.3
- support for Multisite

### 1.1.1
- improved disable wordpress.org snippet

### 1.1.0
- major code restructure
- support for Git Updater
- disabled wordpress.org update checks

### 1.0.6
- updated recommended plugins

### 1.0.5
- added warning for Multisite installations
- updated recommended plugins

### 1.0.4
- tested with WP 4.9
- added support for `define('DISABLE_NAG_NOTICES', true);`

### 1.0.3
- added recommended plugins notice
- added rating request notice

### 1.0.2
- MUST RE-INPUT SETTINGS!!!
- minor code tweaks

### 1.0.1
- removed `noscript` snippet (spammy)

### 1.0.0
- initial release
