#!/usr/bin/env python
"""Usage: ./replace.py FILE..
Replaces some defined patterns.
"""

import re
import sys
import logging

# For layoutExt() removal.
SUBS = [
    (re.compile(r"(?m)^\s*return\s*XmlParser::layoutExt\(\$this(,\s*true)?\);\s*^"), ""),
    ]

# For i:comment renaming.
SUBS = [
    (re.compile(r"i:help\s*>"), "i:tooltip>"),
    (re.compile(r"i:comment\s*>"), "i:help>"),
    ]

def main():
    args = sys.argv[1:]
    if len(args) == 0:
        print >>sys.stderr, __doc__
        sys.exit(1)

    for filename in args:
        data = open(filename).read()
        changed = data
        for pattern, repl in SUBS:
            changed = pattern.sub(repl, changed)

        if changed != data:
            logging.warn("Changing %s", filename)
            output = open(filename, "w")
            output.write(changed)
            output.close()

main()
