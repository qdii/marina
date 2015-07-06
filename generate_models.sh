base_dir="$(pwd)"
for i in boat composition cruise dish ingredient meal unit
do
    capitalized_name=$(echo $i | sed 's/./\U&\E/')
    echo Creating model for: $i
    ${base_dir}/yii gii/model --generateLabelsFromComments=1 --overwrite=1 --tableName="$i" --interactive=0 --generateRelations=1 --enableI18N=1 --modelClass="$capitalized_name"
done
