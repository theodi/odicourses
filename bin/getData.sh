#!/bin/bash
wget --output-document=courses.json "http://contentapi.theodi.org/with_tag.json?type=course"
cd bin
php getInstancesEnriched.php > ../instances.json
