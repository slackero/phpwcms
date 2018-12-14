#####################################################
#
#  PHPWCMS SQL Update
#
#  08.06.2004
#
#####################################################

#
# Tabellenstruktur für Tabelle `phpwcms_fonts`
#

CREATE TABLE `phpwcms_fonts` (
  `font_id` int(11) NOT NULL auto_increment,
  `font_name` text NOT NULL,
  `font_shortname` text NOT NULL,
  `font_filename` text NOT NULL,
  PRIMARY KEY  (`font_id`)
);

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `phpwcms_fonts_colors`
#

CREATE TABLE `phpwcms_fonts_colors` (
  `color_id` int(11) NOT NULL auto_increment,
  `color_name` text NOT NULL,
  `color_value` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`color_id`)
);

# --------------------------------------------------------

#
# Tabellenstruktur für Tabelle `phpwcms_fonts_styles`
#

CREATE TABLE `phpwcms_fonts_styles` (
  `style_id` int(11) NOT NULL auto_increment,
  `style_name` text NOT NULL,
  `style_info` text NOT NULL,
  PRIMARY KEY  (`style_id`)
);
