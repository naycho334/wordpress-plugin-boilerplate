# Find PO files, process each with msgfmt and rename the result to MO
for file in `find ./lang -name "*.po"` ; do msgfmt -o ${file/.po/.mo} $file ; done