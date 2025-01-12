function gerarPDF() {
    const linhasTabela = document.getElementsByTagName('table');

    let janela = window.open('');

    // Estilo - CSS
    let estilo = "<style>" +
        "table, th, td { " +
        "border: 1px solid black; " +
        "border-collapse: collapse;" +
        "} " +
        "\n" +
        "th, td { " +
        "padding: 5px 5px 10px 10px;" +
        "}" +
        "</style>";

    // Cabeçalho
    janela.document.write("<html><head>");
    janela.document.write('<meta charset="UTF-8">');
    janela.document.write('<meta name="viewport" content="width=device-width, initial-scale=1.0"></meta>');
    janela.document.write("<title>Funcionários</title>");
    janela.document.write(estilo);
    janela.document.write("</head>");

    // Título
    janela.document.write("<h3>Lista de Funcionários</h3>");

    // Tabela
    janela.document.write("<table>");
    janela.document.write(" <thead>");
    janela.document.write("   <tr>");
    janela.document.write("     <th>Código</th>");
    janela.document.write("     <th>Nome</th>");
    janela.document.write("     <th>CPF</th>");
    janela.document.write("     <th>RG</th>");
    janela.document.write("     <th>E-mail</th>");
    janela.document.write("     <th>Empresa</th>");
    janela.document.write("     <th>Data Cadastro</th>");
    janela.document.write("     <th>Salário</th>");
    janela.document.write("     <th>Bonificação</th>");
    janela.document.write("   </tr>");
    janela.document.write(" </thead>");

    janela.document.write(" <body>");

    const tbody = linhasTabela[0].getElementsByTagName('tbody');
    const tr = tbody[0].getElementsByTagName('tr');

    for (let linha = 0; linha < tr.length; linha++) {
        janela.document.write("<tr>");

        let td = tr[linha].getElementsByTagName('td');
        for (let celula = 0; celula < 9; celula++) {
            janela.document.write("<td>" + td[celula].innerHTML + "</td>");
        }

        janela.document.write("</tr>");
    }

    janela.document.write(" </body>");
    janela.document.write("</table>");

    janela.document.write("</html>");

    janela.document.close();
    janela.print();
}