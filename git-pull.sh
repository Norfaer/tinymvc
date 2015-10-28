#!/bin/bash
cd ${PWD}
git pull
chown tinymvc:tinymvc ${PWD} -R
exit 0