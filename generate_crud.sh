base_dir="$(pwd)"
for i in boat composition cruise dish ingredient meal unit product fraction proportion vendor auth course
do
    capitalized_name=$(echo $i | sed 's/./\U&\E/')
    echo Creating CRUD for: $i
    ${base_dir}/yii gii/crud --controllerClass="app\\controllers\\${capitalized_name}Controller" --enableI18N=1 --overwrite=1 --searchModelClass="app\\models\\${capitalized_name}Search" --modelClass="app\\models\\${capitalized_name}" --interactive=0 --viewPath="@app/views/$i"
done
