var data = [
  {id: 1, nome: "Bruno"},
  {id: 2, nome: "João"},
  {id: 3, nome: "Marcos"},
  {id: 4, nome: "Leonardo"},
  {id: 5, nome: "Renato"},
]

function exportData() {
  alasql.promise("SELECT * INTO XLSX('cities.xlsx',{headers:true}) FROM ?",[data]);
}