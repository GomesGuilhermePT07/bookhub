# PRÓXIMA COISA A FAZER:

- verificar se já tem o livro no carrinho antes de adicionar para não haver repetições no carrinho
- verificar todos os erros e melhorias a fazer antes de começar a desenvolver as abas do site

# ----------------------------//ERROS E MELHORIAS\\-------------------------------

<!-- - Detalhes da conta passa para "detalhes_conta.php" e o arquivo é ".html"
- Inicío sessão numa conta, inicia automaticamente na outra aba na mesma conta (facilmente resolvido, apresento só e apenas numa aba) 
- login com senha errada passa para página à parte ("captar_login.php") --> 
^
|
\\ ADICIONAR ERROS NA INTERFACE DO UTILIZADOR \\
- slider não carrega a 2ª e a 3ª imagem
<!-- - adicionar funcionalidade das atividades feitas pelos utilizadores \\ TESTAR, CORRIGIR E ACABAR \\ -->
<!-- - verificar se já tem o livro no carrinho antes de adicionar para não haver repetições no carrinho 
- só pode ter acesso ao carrinho se tiver sessão iniciada-->
<!-- - logout passa diretamente para a página de login 
- tento entrar manualmente no "index.php", redireciona para o "index_user.php" 
- ao clicar para ver o resumo todo no modal, vai parar a um caminho estanho desconhecido 
- carrinho sem sessão iniciada ainda tem as palavras "registar" e "entrar" nos botões invés dos icons, tanto no user como no admin -->
<!-- - aparece uma mensagem de erro na página de carrinho sem a sessão iniciada -->
- acertar o slider
- acertar a ordem de aparição dos elementos na página inicial
- footer está acima dos livros
- página de registo com os icons de ver password ficam em baixo com o erro ativado. Centrar!
<!-- - utilizador sem sessão iniciada tem de ter uma mensagem no carrinho ("Inicie sessão para ver o seu carrinho") -->
<!-- - "index.php" sem sessão iniciada ainda está com a palavra "registar" invés do icon -->
- "index.php" -> carrinho -> home -> "index_user.php"! \\ \\ CORRIGIR \\ \\ 
- adicionar mensagens de erro na interface do utilizador e não no servidor (mensagens mais amigáveis)
- adicionar livro no site = fazer refresh para aparecer! \\ \\ CORRIGIR \\ \\ (adicionar refresh automatico)
- criar sidebar para o carrinho, para quando passar o mouse por cima, aparecer um sidebar do lado direito da tela com os livros que estão no carrinho
- melhorar a página em que mostra cada livro detalhadamente

\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

<!-- ## O BOTÃO "REMOVER LIVRO" NÃO ESTÁ A REMOVER DA BASE DE DADOS! CORRIGE ISSO AGORA!!!!!! -->

\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

ACABAR OS ESTILOS DA PÁGINA DOS DETALHES DO LIVRO 

\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
<!-- - "detalhes_conta.php" não tem icon do home -->
<!-- - "detalhes_conta.php" ainda está com os artigos entre "()" -->
<!-- - "detalhes_conta.php" não mostra a quantidade de artigos -->
<!-- - só pode adicionar livro ao site se estiver logado como administrador -->
- melhorar a aparência do slider
- botão de "adicionar ao carrinho" apenas na página de cada livro detalhadamente
<!-- - acertar o header com a cena da percentagem adicionada -->





# ----------------------------//ERROS E MELHORIAS\\-------------------------------

# SISTEMA DE GERENCIAMENTO DE REQUISIÇÕES: 

1- aparece todas as requisições do site (nome do livro, id do utilizador, email, id da requisição, data da requisição e de entrega, estado da requisição(entregue ou por entregar));

2- barra para pesquisar através do email ou do nome do utilizador;

3- aparecer todas as requisições do utilizador (com os atributos já mencionados anteriormente) e aparecer um botão de "entregar" para o utilizador e um botão de "devolvido" para o administrador;

    "entregar" --> notificação para o administrador --> botão "pode devolver" para o administrador --> notificação para o utilizador para ir à biblioteca.

    "devolvido" --> modal para preencher os dados com o nome do livro e etc com base no código ISBN do mesmo (desta vez ligado à base de dados) --> "devolver livro".

4- deletar a requisição da tabela "requisições" da base de dados e consequentemente, do site.

# DADOS A PREENCHER NA ENTREGA DO LIVRO:

- nome do livro;
- ISBN do livro;
- autor do livro;
- nº de páginas;
- resumo;
- quantidade;
- nota do utilizador/feedback (opcional).