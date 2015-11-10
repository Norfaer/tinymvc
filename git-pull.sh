#!/bin/bash
cd ${PWD}
git stash
git pull
chown tinymvc:tinymvc ${PWD} -R
exit 0