base="$PWD"
cd $base/img
u=seanax26
p=crack4206
sessfile=$base/tmp/.session-$u
opts=" --igtv --no-compress-json";# -f $sessfile":
if [ -z ${1} ]; then
for dir in *; do
echo "User: $dir";
instaloader --login=$u  --password=$p $opts  $dir;
mv "$dir/" "../img/$dir/"
sleep 10m;
done;
else
echo "User: $1";
instaloader --login=$u  --password=$p $opts $1
#mv "$1/" "../img/$1/"
