<?php
/**
 * Portugese Language file for PhpGedView.
 *
 * phpGedView: Genealogy Viewer
 * Copyright (C) 2002 to 2008  PGV Development Team.  All rights reserved.
 *
 * Modifications Copyright (c) 2010 Greg Roach
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @package PhpGedView
 * @author Maurício Menegazzo Rosa
 * @author Anderson Wilson and Clovis Bombardelli
 * @version $Id$
 */

if (!defined('PGV_PHPGEDVIEW')) {
	header('HTTP/1.0 403 Forbidden');
	exit;
}

$pgv_lang["edit_add_unlinked_source_help"]="Use este link para adicionar uma nova Fonte ao banco de dados, sem contudo criar uma referência a ela. Enquanto não for referenciada, a nova Fonte somente aparecerá na Lista de Fontes.";
$pgv_lang["link_person_id_help"]="Este campo deve conter a ID da pessoa referenciada (ex.: I100).";
$pgv_lang["link_gedcom_id_help"]="Use esta seção para selecionar a identificação alternativa do banco de dados que faça referência a pessoa desejada.";
$pgv_lang["link_remote_site_help"]="Nesta seção você especifica os parâmetros necessários à conexão com o Site Remoto que contém os dados referenciados. Você pode selecionar o Site de uma lista de Sites já conhecidos, ou informando um novo em Endereço do Site e ID do Banco de Dados.<br /><br />No campo <b>Endereço do Site</b>, informe a URL de acesso ao arquivo com a descrição dos serviços Web (WDSL), utilizado pelo PhpGedView para acessar os dados no Site Remoto. Exemplo da URL: <u>http://www.remotesite.com/phpGedView/genservice.php?wsdl</u><br /><br />O campo <b>ID do Banco de Dados</b> é usado, opcionalmente, para informar uma identificação alternativa do banco de dados para Sites Remotos. Para sites PhpGedView, este é o nome do arquivo GEDCOM. <br /><br />Os campos <b>Usuário</b> e <b>Senha</b> só precisam ser informados caso o banco de dados assim o peça.<br /><br /><i>Obs.: Sites PhpGedView remotos devem usar a versão 4.0 or superior.</i>";
$pgv_lang["link_remote_location_help"]="Neste campo, informe se os dados da pessoa referenciada estão neste mesmo site, porém em outro banco de dados, ou se os dados desta pessoa estão em um Site Remoto.<br /><br />Se no mesmo site, informe a identicação do banco de dados e da pessoa.<br /><br />Para sites remotos é necessário informar a URL, a identicação do banco de dados e da pessoa.";
$pgv_lang["link_remote_rel_help"]="Use esta opção para definir o relacionamento da pessoa de um site remoto com a pessoa deste site. Exemplo: selecioando <i>Pai</i> significa que a pessoa do site remoto, é pai da pessoa deste site.";
$pgv_lang["link_remote_help"]="Use este formulário para interligar pessoas deste GEDCOM com pessoas em outros GEDCOMs ou de Sites Remotos.<br /><br />Primeiro selecione o tipo de relacionamento, depois selecione ou informe o Site Remoto, depois informe a ID da pessoa relacionada. O PhpGedView buscará automaticamente as informações do Site Remoto quando necessárias. Estas informações <u>não</u> se tornam parte de seu banco de dados genealógico; elas permanecem no site remoto, porém são incorporadas às páginas que fazem referência a esta pessoa remota.<br /><br />Para mais informações, veja a Ajuda específica ao lado de cada campo ou acesse o tutorial em inglês: <a href=\"http://wiki.phpgedview.net/en/index.php/How_to_Remote_Link\" target=\"_blank\">http://wiki.phpgedview.net/en/index.php/How_to_Remote_Link</a>.";
$pgv_lang["edit_ABBR_help"]="Este campo é usado para guardar a forma abreviada de um título. Este campo é utilizado em conjunto com o campo de título das Fontes. O padrão é usar primeiro o título e depois a forma abreviada.<br /><br />De acôrdo com o padrão GEDCOM 5.5, \"esta informação é utilizada como forma abreviada de um título e para ordenação, preenchimento, e acesso aos registros de Fonte (pg 62).\"<br /><br />Para o PhpGedView, a forma abreviada é opcional, mas para outros programas é obrigatório.";
$pgv_lang["edit_ROMN_help"]="Em muitas culturas é costume a pessoa, além de ter seu nome escrito com os caracteres de seu idioma, também ter o nome escrito com caracteres romanos (ocidentais). Você pode utilizar este campo para informar o nome com caracteres romanos. Esta versão do nome também aparecerá nas listas e gráficos.<br /><br />Hebráico, Grego, Russo e Árabe, são exemplos de idiomas onde a pessoa em geral tem seu nome escrito das duas formas.|";
$pgv_lang["edit__HEB_help"]="Muitas culturas tem por tradição dar um nome com os caracteres do idioma local e outro com caracteres romanos, tal como o Português.<br /><br />Use o campo Nome para caracteres romanos e este para o nome com caracteres Grego, Hebreu, Russo, Arabe, etc. As duas versões aparecerão nas listas e gráficos.<br /><br />Este campo não é restrito a caracteres Hebraicos.";
$pgv_lang["edit_SEX_help"]="Selecione o sexo da pessoa na lista. A opção <b>desconehcido</b> só deve ser usada quando realmente o sexo não for sabido.";
$pgv_lang["edit_NAME_help"]="Este é o campo mais importante de uma pessoa.<br /><br />Este campo será preenchido automaticamente a medida que os demais campos forem sendo preenchidos, mas depois altere-o como quiser.<br /><br />O nome deve estar de acôrdo com o formato GEDCOM 5.5.1, isto é, o sobrenome deve estar entre barras \"/\". Exemplo, o nome \"John Robert Finlay Jr.\" deve ser infromado como \"John Robert /Finlay/ Jr.\".";
$pgv_lang["edit_add_unlinked_person_help"]="Use este formulário para adicionar uma pessoa não ligada a nenhuma outra pessoa desta árvore.<br /><br />Posteriormente esta pessoa poderá ser ligada a outras através da ficha Parentes Próximos da página Informação Pessoal.";
$pgv_lang["edit_URL_help"]="Informe o endereço URL incluindo http://.<br /><br />Exemplo de uma URL:<b>http://www.phpgedview.net/</b> Este campo é opcional e pode ser deixado em branco.";
$pgv_lang["edit_EMAIL_help"]="Informe o endereço de email.<br /><br />Exemplo: <b>name@hotmail.com</b> Este campo é opcional e pode ser deixado em branco.";
$pgv_lang["edit_FAX_help"]="Informe o FAX incluindo o DDI e DDD, mas não inclua a operadora.<br /><br />Este campo é ocpional e pode ser deixado em branco. Exemplo: Um fax na cidade do Rio de Janeiro-Brasil seria +55 21 2233-4455 na Alemanha +49 25859 56 76 89 e nos EUA ou Canadá +1 888 555-1212.";
$pgv_lang["edit_PHON_help"]="Informe o Telefone incluindo o DDI e DDD, mas não inclua a operadora.<br /><br />Este campo é ocpional e pode ser deixado em branco. Exemplo: Um telefone na cidade do Rio de Janeiro-Brasil seria +55 21 2233-4455 na Alemanha +49 25859 56 76 89 e nos EUA ou Canadá +1 888 555-1212.";
$pgv_lang["edit_ADDR_help"]="Informe o endereço da mesma forma como você escreveria no envelope.<br /><br />Este campo é opcional e pode ser deixado em branco.";
$pgv_lang["edit_GIVN_help"]="Informe o nome da pessoa sem o sobrenome. Por exemplo uma pessoa cujo o nome completo é  \"José Marcos Silva\", você deve informar \"José Marcos\" neste campo";
$pgv_lang["edit_SPFX_help"]="Informe ou selecione da lista, as palavras que precedem o sobrenome. Exemplos: <b>von</b> Braun, <b>van der</b> Kloot, <b>de</b> Graaf, etc.";
$pgv_lang["edit_SURN_help"]="Informe o sobrenome da pessoa. Exemplo.: para o nome \"John Robert Finlay\", o sobrenome informado deve ser \"Finlay\"";
$pgv_lang["edit_NSFX_help"]="Este campo é opcional e serve para informar sufixos do nome. Exemplo: \"Filho\", \"Junior\", \"Jr.\", \"Neto\", e \"III\".";
$pgv_lang["edit__MARNM_help"]="Informe o nome de casado desta pessoa seguindo as mesmas regras do campo Nome. Este campo é opcional e pode ser deixado em branco.";
$pgv_lang["context_help"]="Para mais Ajuda clique em <b>?</b> localizado ao lado dos items de cada página.";
$pgv_lang["register_gedcomid_help"]="Cada pessoa no banco de dados possui uma ID única. Neste campo, informe sua ID e caso não saiba, preencha o campo Comentários para que o Administrador possa descobrir sua ID e informar-lhe posteriormente.";
$pgv_lang["register_comments_help"]="Use este campo para informar ao Administrador as razões para seu acesso à árvore e qual seu relacionamento com as pessoas deste site. Pode ser utilizado também para qualquer outro comentário pessoal ao Administrador.";
$pgv_lang["utf8_ansi_help"]="PhpGedView utiliza o conjunto de caracteres UTF-8. Alguns programas, tais como o Family Tree Maker, não importam arquivos GEDCOM neste formato. Marcando esta opção,  fará com que o arquivo seja convertido de <b>UTF-8</b> para <b>ANSI (ISO-8859-1)</b>.<br /><br />Consulte o manual de seu programa para saber qual o formato mais adequado.<br /><br />Obs.: Para que os caracteres especiais permaneçam inalterados, será necessário manter o formato em UTF-8 e usar alguma ferramenta para converter para o formato de seu programa. Consulte o fabricante de seu programa para esclarecimentos.";
$pgv_lang["remove_tags_help"]="Marcando esta opção fará com que tags específicas do PhpGedView sejam removidas.<br /><br />Estas tags incluem as tags <b>_PGVU</b> que identifica o usuário que alterou um registro e a tag <b>_THUM</b> que informa qual imagem deve ser usada como Miniatura.<br /><br />Tags especiais podem causar Erros e Avisos no processo de importação do arquivo GEDCOM por outro programa genealógico.";
$pgv_lang["download_zipped_help"]="Marcando esta opção o arquivo GEDCOM será compactado no formato ZIP antes dele ser enviado para o seu computador. Isto reduzirá o tamanho e o tempo de transmissão do arquivo, mas será necessário descompacta-lo antes de importa-lo para seu programa genealógico.<br /><br />Esta opção é muito útil para arquivos GEDCOM muito grandes, que podem exceder o tempo máximo de transferência, acarretando em arquivos imcompletos.";
$pgv_lang["remember_me_help"]="Marcando esta opção PhpGedView lembrará de você na sua próxima visita e não será necessário identificar-se novamente. Isto é possível através da gravação de um cookie em seu computador, que o identificará na sua próxima visita.<br /><br />Na próxima visita, você poderá ver as informações, porém para alterar qualquer dado será necessário identificar-se informando o nome de usuário e senha.<br /><br /><b>Não marque esta opção se você estiver utilizando um computador público ou que você compartilhe com outros; caso contrário qualquer um poderá acessar o site deste computador.</b>";
$pgv_lang["edit_NCHI_help"]="Informe a quantidade de filhos desta pessoa ou família. Este campo é opcional.";
$pgv_lang["edit_TIME_help"]="Informe a hora deste evento, no formato 24 horas. Meia-noite é 00:00. Exemplos: 04:50 13:00 20:30.";
$pgv_lang["edit_NOTE_help"]="Notas é um texto livre que é exibido na seção Detalhes do Fato.";
$pgv_lang["edit_CEME_help"]="Informe o nome do cemitério ou local onde a pessoa está enterrada.";
$pgv_lang["edit_ASSO_help"]="Informe a ID da testemunha. Exemplo.: I51";
$pgv_lang["edit_RELA_help"]="Selecione um relacionamento na lista. Selecionando <b>Padrinho</b> significa que <i>a testemunha é o padrinho desta pessoa</i>.";
$pgv_lang["show_spouse_help"]="Exibir cônjuges no gráfico de descendentes é opcional, porque em geral torna o entendimento do gráfico mais difícil. Habilitando esta opção, os cônjuges serão exibidos.";
$pgv_lang["reorder_families_help"]="A ordem em que os casamentos de uma pessoa aparece, na ficha Parentes Próximos, é a mesma ordem dos registros do arquivo GEDCOM.<br /><br /> Esta opção permite que você re-ordene os casamentos exibidos em Parentes Próximos da forma desejada. Caso a data de casamento de todos seja conhecida, basta Para ordenar pela data de casamento, basta clicar o botão correspondente.";
$pgv_lang["edit_TYPE_help"]="O campo Tipo é usado para informações adicionais sobre o item. Em geral o campo é de texto livre e permite a entrada de qualquer tipo de informação.";
$pgv_lang["edit_TEMP_help"]="Para LDS ordinances, este campo guarda o Templo onde ocorreu a cerimônia.";
$pgv_lang["edit_STAT_help"]="Este campo é opcional e em geral usado para LDS ordinances, enquanto eles percorrem o programa TempleReady.<br /><br />|";
$pgv_lang["edit__PRIM_help"]="Use este campo para informar que esta mídia é a de destaque para esta pessoa. A imagem de destaque é a que é usada nos gráficos en na página da Pessoa.";
$pgv_lang["edit__THUM_help"]="Em geral não há necessidade de usar este campo, já que o PhpGedView criará automaticamente a Miniatura da imagem enviada.<br /><br />Porém, caso a miniatura automática tenha perdido os detalhes, você pode montar sua própria miniatura e envia-la.<br /><br />Quando esta opção está em <b>Sim</b>, PhpGedView utiliza a imagem especificada como a miniatura, não fazendo qualquer tipo de transformação ou verificação do tamanho da mesma.";
$pgv_lang["edit_TITL_help"]="Informe o título do item editado. Para mídias, informe um título que descreva o conteúdo da mída para o usuário.";
$pgv_lang["edit_FILE_help"]="Este campo é fundamental para saber qual mídia usar. No mínimo deve ser informado o nome do arquivo. <br /><br />Use <b>Localizar Mídia</b> para ajuda-lo a encontrar a mídia, caso o arquivo já esteja gravado no servidor.<br /><br />Leia <a href=\"readme.txt\" target=\"_blank\"><b>Readme.txt</b></a> para mais informações.";
$pgv_lang["edit_FORM_help"]="Este campo é opcional. É usado para informar o formato da mídia, que alguns programas se utilizam para saber como tratar a mídia.";
$pgv_lang["generate_thumb_help"]="Seus sistema esta habilitado a gerar miniaturas automaticamente a partir de imagens do tipo JPG, GIF, e PNG. Os formatos reconhecidos estão listados ao lado da caixa da opção.<br /><br />Marcando esta opção o sistema tentará gerar miniaturas para as imagens enviadas. Caso queira enviar sua própria miniatura, não marque esta opção.";
$pgv_lang["edit_add_NOTE_help"]="Esta seção permite a adição de uma nova nota ao fato que está sendo editado. O campo Notas permite texto livre e é exibido na seção Detalhes do Fato.";
$pgv_lang["edit_add_SOUR_help"]="Esta seção permite adicionar ao fato que está sendo editado, uma citação a uma fonte.<br /><br />No campo Fonte, informe a ID da Fonte. Clique em <b>Nova Fonte</b> para criar uma nova fonte se necessário. No Detalhe da Citação, você pode por exemplo informar a página da Fonte, ou qualquer outra informação que facilite encontra-la na Fonte. O campo Texto é reservado à transcrição parcial da Fonte.";
$pgv_lang["edit_add_ASSO_help"]="Nova Testemunha permite a ligação de um fato a uma pessoa da mesma árvore. Pode ser usado por exemplo para indicar que alguém da árvore é Padrinho de Batismo de outro, ou que determinada pessoa estava presente ao enterro de outra, etc...";
$pgv_lang["edit_QUAY_help"]="Este campo é utilizado para informar a credibilidade das informações contidas na Fonte. Programas de Genealogia usam números, por exemplo: <b>1</b> pode significar que os dados são confiáveis, <b>2</b> que são discutíveis e <b>3</b> que não são críveis.";
$pgv_lang["edit_PAGE_help"]="No Detalhe da Citação informe qualquer coisa que facilite a achar a informação na Fonte.";
$pgv_lang["edit_TEXT_help"]="Neste campo transcreva ou descreva o texto da Fonte que cita a pessoa em questão.";
$pgv_lang["edit_SOUR_help"]="Este campo permite excluir ou alterar a qual fonte este fato esta relacionado. Ao lado da ID da Fonte, aparece o título correspondente a esta ID. Use <b>Procurar ID</b> para saber a ID de outra Fonte. Para excluir a referência a Fonte, basta deixar este campo em branco.";
$pgv_lang["edit_edit_raw_help"]="Nesta página é possível editar os registros do GEDCOM diretamente. Use este recurso com muito cuidado e somente se você conhece o formato GEDCOM 5.5.1. Para saber mais sobre este o formato, leia o tópico da Ajuda <b>Arquivo GEDCOM</b>.<br /><br />O PhpGedView oferece várias formas para adicionar, alterar e excluir as informações, porém em algumas situações pode ser necessário editar diretamente os registros, mas sempre que possível use os formulários do PHPGedView. Suas alterações passam por uma validação simples para saber se estão de acôrdo com o formato GEDCOM 5.5.1 e em seguida são gravadas e o registro CHAN atualizado.";
$pgv_lang["add_from_clipboard_help"]="O PhpGedView permite a cópia de até 5 fatos para a Área de Transferência. A partir do menu Adicionar Novo Fato, você poderá colar o fato da Área de Transferência para a Pessoa que está sendo alterada. Este recurso é muito útil quando é necessário incluir fatos similares para várias pessoas.";
$pgv_lang["edit_PLAC_help"]="Locais devem ser informados seguindo o padrão genealógico. O padrão é primeiro informar o local mais específico, seguido de um menos específico e assim sucessivamente, usando virgulas para separa-los. Exemplo.: \"Rio de Janeiro, RJ, Brasil\".<br /><br />A primeira parte, \"Rio de Janeiro,\" é a cidade ou municípiois onde o evento aconteceu. Você poderia ser ainda mais específico e informar o Bairro antes da cidade, por exemplo \"Copacabana,\". A parte seguinte , \"RJ,\" é o  Estado e \"Brasil\" o país. É importante informar todos os níveis, \"Rio de janeiro\" não é o mesmo que \"Rio de Janeiro, RJ, Brasil\".<br /><br />Caso não saiba um nível intermediário, deixe um espaço entre virgulas. Suponha que no exemplo anterior você não soubesse o Estado onde fica o Rio de Janeiro, você informaria: \"Rio de Janeiro, , Brasil\". Suponha que você só saiba o Estado e o País. Neste caso você informaria: \", RJ, Brasil\". <br /><br />Use o link <b>Procurar Local</b> para ajuda-lo a encontrar locais já cadastrados.";
$pgv_lang["edit_add_parent_help"]="Use esta página para adicionar uma mãe ou pai para a pessoa selecionada. Informe o nome desta nova pessoa, a data de nasciemnto e falecimento. Senão souber deixe em branco.<br /><br />Para acrescentar outros fatos e eventos a esta pessoa, primeiro adicione-a e depois clique no nome dela para ver a página Informação Pessoal onde você poderá adicionar mais dados a ela.";
$pgv_lang["edit_add_spouse_help"]="Use esta página para adicionar um marido ou uma esposa à pessoa selecionada. Informe o nome da pessoa, a data de nascimento e falecimento. Senão souber deixe em branco.<br /><br />Para acrescentar outros fatos e eventos a esta nova pessoa, primeiro adicione-a e depois clique no nome dela para ver a página Informação Pessoal onde você poderá adicionar mais dados a ela.";
$pgv_lang["edit_death_help"]="Informe a data do falecimento utilizando o formato padrão da genealogia (1 DEZ 2004) ou clique no calendário e selecione a data desejada. Em seguida informe o local onde a pessoa faleceu ou use o link <b>Procurar Local</b> para selecionar um dos locais já cadastrados em seu banco de dados.";
$pgv_lang["edit_birth_help"]="Informe a data do nasciomento utilizando o formato padrão da genealogia (1 DEZ 2004) ou clique no calendário e selecione a data desejada. Em seguida informe o local onde a pessoa nasceu ou use o link <b>Procurar Local</b> para selecionar um dos locais já cadastrados em seu banco de dados.";
$pgv_lang["edit_sex_help"]="Selecione o sexo da pessoa na lista. A opção <b>desconehcido</b> só deve ser usada quando realmente o sexo não for sabido.";
$pgv_lang["edit_name_help"]="Este é o campo mais importante de uma pessoa.<br /><br />Este campo será preenchido automaticamente a medida que os demais campos forem sendo preenchidos, mas depois altere-o como quiser.<br /><br />O nome deve estar de acôrdo com o formato GEDCOM 5.5.1, isto é, o sobrenome deve estar entre barras \"/\". Exemplo, o nome \"John Robert Finlay Jr.\" deve ser infromado como \"John Robert /Finlay/ Jr.\".";
$pgv_lang["edit_suffix_help"]="Este campo é opcional e serve para informar sufixos do nome. Exemplo: \"Filho\", \"Junior\", \"Jr.\", \"Neto\", e \"III\".";
$pgv_lang["edit_surname_help"]="Informe o sobrenome da pessoa. Exemplo.: para o nome \"John Robert Finlay\", o sobrenome informado deve ser \"Finlay\"";
$pgv_lang["edit_NICK_help"]="Este campo é opcional e serve para informar o apelido da pessoa.<br /><br />Formas de adicionar um apelido:<ul><li>Selecione <b>Alterar Nome</b> e informe o apelido e salve</li><li>Selecione <b>Adicionar Nome</b> informe o apelido, o nome e salve</li><li>Selececione <b>Editar Registro GEDCOM</b> para adiconar múltiplos registros [2&nbsp;NICK] subordinados ao registro principal [1&nbsp;NAME].</li></ul>";
$pgv_lang["edit_given_name_help"]="Informe o nome da pessoa sem o sobrenome. Por exemplo uma pessoa cujo o nome completo é  \"José Marcos Silva\", você deve informar \"José Marcos\" neste campo";
$pgv_lang["edit_NPFX_help"]="Este campo é opcional e serve para informar um prefixo do nome, por exemplo \"Dr.\", \"Padre\", \"Major\", etc.";
$pgv_lang["edit_add_child_help"]="Nesta página você pode adicionar um filho a esta família. Informe o nome, a data de nascimento e falecimento, senão souber pode deixar em branco.<br /><br />Após adiconar a pessoa à família, outros fatos e eventos poderão ser adicionados a ela em sua página Informação Pessoal.";
$pgv_lang["review_changes_help"]="Este bloco lista todos os registros com alterações pendentes e que precisam ser revisadas.";
$pgv_lang["index_top10_pageviews_help"]="Este bloco lista as 10 pessoas, famílias, ou fontes mais visitados do site. Este bloco só funcionará se o Administrador habilitou a opção \"Contador de Hit de Itens\".";
$pgv_lang["useradmin_user_default_tab_help"]="Esta opção permite selecionar a ficha aberta automaticamente ao acessar a página Informação Pessoal. Se for dado ao usuário permissão para alterar sua conta, ele poderá modificar esta opção posteriormente.";
$pgv_lang["edituser_user_default_tab_help"]="Esta opção permite selecionar qual ficha é aberta automaticamente quando a página Informação Pessoal é acessada.";
$pgv_lang["reorder_children_help"]="Os filhos são exibidos na ordem em que foram informados, pois nem sempre a data de nascimento é conhecida.<br /><br />Esta opção permite ordenar as crianças da forma como se queira. Para ordenar por data de nascimento, clique no botão correspondente.";
$pgv_lang["upload_media_folder_help"]="A configuração do GEDCOM permite até #GLOBALS[MEDIA_DIRECTORY_LEVELS]# niveis de subpastas em <b>#GLOBALS[MEDIA_DIRECTORY]#</b>, onde os arquivos de mídia são gravados. Isto permite uma melhor organização das mídias minimizando a preocupação em manter nomes unicos para as mídias.<br /><br />Neste campo informe o nome da pasta de destino das mídias enviadas para o servidor, letras maiusculas e minusculas fazem diferença, caso já não exista a pasta é criada automaticamente. A informação será truncada, se for informado mais do que #GLOBALS[MEDIA_DIRECTORY_LEVELS]# niveis definidos pela configuração do GEDCOM.<br /><br />As miniaturas serão criadas em uma estrutura idêntica à das mídias, iniciando em <b>#GLOBALS[MEDIA_DIRECTORY]#thumbs/</b>.";
$pgv_lang["help_header"]="Informação sobre:";
$pgv_lang["more_help"]="<br />A Ajuda ao Contexto está disponível em cada janela; assegure-se de que a opção <b>Exibir link de Ajuda</b> no Menu de Ajuda está habilitada, e clique na <b>?</b> correspondente ao assunto.";
$pgv_lang["more_config_help"]="<br /><b>Mais ajuda</b><br />Para mais Ajuda clique em <b>?</b> localizado ao lado dos items de cada página.";
$pgv_lang["start_admin_help"]="+++ Início Info Extra do Admin +++";
$pgv_lang["end_admin_help"]="--- Fim Info Extra do Admin ---";
$pgv_lang["multiple_help"]="<center>--- Este é um texto genérico para páginas múltiplas ---</center>";
$pgv_lang["header_general_help"]="<div class=\"name_head\"><center><b>INFORMAÇÂO GERAL</b></center></div>";
$pgv_lang["best_display_help"]="PhpGedView foi formatado para uma resolução de 1024x768 pixels. Este é o tamanho mínimo para que todas as informações sejam exibidas sem que haja necessidade de rolagem horizontal ou vertical da tela.";
$pgv_lang["preview_help"]="Clicando no link <b>Exibir no formato de impressão<b> fará com que todos os itens que não ficam bem na impressão sejam removidos (menus, caixa de texto, links extras, as interrogações de Ajuda, etc.) e acrescentará, no final da página, um link para Imprimir e outro para retornar ao modo de exibição anterior.";
$pgv_lang["readme_help"]="Leia <a href=\"readme.txt\" target=\"_blank\"><b>Readme.txt</b></a> para mais informações.";
$pgv_lang["is_user_help"]="--- Este é o mesmo texto de Ajuda que os visitantes lerão. --- <br />--- Por economia, não há texto especial para o Administrador. ---";
$pgv_lang["header_search_help"]="Esta caixa de pesquisa é pequena, mas poderosa. Com ela você pode pesquisar por quase qualquer informação da árvore. Informe o que deseja pesquisar e clique o botão <b>></b> ou <b>Pesquisar</b>, a página com o resultado da pesquisa será exibida e nela você encontrará mais Ajuda sobre as opções de pesquisa.";
$pgv_lang["menu_help"]="<div class=\"name_head\"><center><b>MENUS</b></center></div><br />No topo das páginas existem menus cujas opções apresentam sub-menus. Para acessar estes sub-menus basta passar o mouse sobre as opções.<br /><br />Se o icone da opção for clicado, será mostrada a página referente a primeira opção do sub-menu.<br /><br />Em geral, as seguintes opções estão disponíveis:<ul><li><a href=\"#menu_fam\">Página Inicial</a><br /><li><a href=\"#menu_myged\">Meu Portal</a><br /><li><a href=\"#menu_charts\">Gráficos</a><br /><li><a href=\"#menu_lists\">Listas</a><br /><li><a href=\"#menu_annical\">Calendário</a><br /><li><a href=\"#menu_clip\">Extração de Dados</a><br /><li><a href=\"#menu_search\">Pesquisar</a><br /><li><a href=\"#menu_help\">Ajuda</a></ul>";
$pgv_lang["menu_famtree_help"]="Todas os Bancos de Dados (B.D.) disponíveis neste site estão listadas neste menu. Cada B.D. pode ser sua própria página de Boas-Vindas, como esta. Se só houver um B.D., não haverá sub-menu abaixo do icone de Boas-Vindas.";
$pgv_lang["menu_myged_help"]="Se você se identificou, este menu pode incluir:<ol><li>Meu Portal<br /> - configuração de sua página iniical.</li><br /><li>Minha Conta<br /> - edição de seus dados pessoais.</li><br /><li>Minha Árvore<br /> - árvore genealógica da pessoa definida em sua configuração.</li><br /><li>Meus Dados<br /> - link para Informação Pessoal  contendo seus dados genealógicos.</li></ol>";
$pgv_lang["menu_clip_help"]="You will see this item in the menu bar only when the administrator has enabled this feature.<br /><br />The Carrinho de Recortes allows you to store information about individuals, families, and sources in a temporary file that you can later download in GEDCOM 5.5.1 format.";
$pgv_lang["menu_search_help"]="A Página de Pesquisa é uma versão mais poderosa do que a Caixa de Pesquisa encontrada no topo de cada página.";
$pgv_lang["menu_help_help"]="#pgv_lang[help_help_items]#";
$pgv_lang["index_portal_head_help"]="<div class=\"name_head\"><center><b>PÁGINA de BOAS-VINDAS</b></center></div>";
$pgv_lang["index_login_help"]="É possivel identificar-se em quase todas as páginas do site. Normalmente isso é feito na primeira página, pois informações privilegiadas só são exibidas para usuários devidamente identificados.<br /><br />Para identificar-se basta informar seu <b>nome de usuário</b>, sua <b>senha</b> e clicar no botão correspondente.";
$pgv_lang["index_events_help"]="Este bloco exibe o aniversário de eventos que estão para acontecer.<br /><br />O Administrador determina o número de dias que o bloco antecipará. Através da configuração do bloco, você pode restringir quais eventos serão exibidos no bloco.";
$pgv_lang["index_onthisday_help"]="Este bloco é similar ao bloco \"Próximos Eventos\", a diferença é que este mostra os eventos de hoje.";
$pgv_lang["index_add_favorites_help"]	= "Este formulário permite adicionar um novo favorito a sua lista de favoritos. Informe a ID da pessoa, da família ou fonte que deseja como favorito, ou informe uma URL e um título. O campo NOTA é opcional, podendo ser usado para descrever o favorito. O texto do campo NOTA será exibido no bloco Favoritos logo após o item.";
$pgv_lang["index_stats_help"]="Este bloco exibe estatíticas do arquivo GEDCOM. Se precisar de mais informações envie um email para o contato no final desta página.";
$pgv_lang["index_gedcom_news_help"]="Este bloco permite ao Administrador publicar notícias e avisos de interesse do site.<br /><br />Se você deseja publicar alguma coisa neste bloco, entre em contato com o Administrador pelo link no final desta página.";
$pgv_lang["recent_changes_help"]="Este bloco utiliza da tag CHAN do arquivo GEDCOM para exibe as últimas atualizações feitas no site.";
$pgv_lang["gedcom_news_limit_help"]="Você pode definir o número máximo de notícias exibidas no bloco de Notícias e com isso reduzir o tamanho do bloco.<br /><br />Esta opção determina se há limite, se o limite é pela idade da notícia ou se é pela qtde de notícias.";
$pgv_lang["gedcom_news_archive_help"]="O Administrador \"escondeu\" alguns artigos para reduzir o espaço ocupado pelo Bloco de Notícias. Você pode torna-los visíveis clicando no link <b>Exibir arquivo</b>.";
$pgv_lang["index_htmlplus_help"]="Este bloco permite ao Administrador adicionar informação ao Indice ou ao Portal. Seu uso é similar aos dos blocos de HTML, Notícias e Estatísticas, porém o Administrador tem mais controle sobre a formatação do bloco.";
$pgv_lang["index_htmlplus_title_help"]="This text should be blank or very brief. When blank, the Advanced HTML block will show on the Index or Portal page as a plain block, just like the HTML block does. When there is text, the Advanced HTML block will show like all the other blocks, complete with a block title bar containing the text you enter here.";
$pgv_lang["help_faq_help"]="<dl><dt><b>Lista de FAQs</b></dt><dd>A página de FAQs (Perguntas mais frequentes), contém uma lista de perguntas e respostas sobre o uso deste site genealógico.<br /><br />O Administrador do site é responsável pelo conteúdo e ordenação desta lista.</dd></dl>";
$pgv_lang["help_HS_help"]="<dl><dt><b>Procurar texto de Ajuda</b></dt><dd>É possível pesquisar a Ajuda do PhpGedView. O recurso Procurar texto de Ajuda proporciona um alto grau de controle sobre a forma a pesquisa funciona; você deveria ser capaz de encontrar o que procura facilmente.</dd></dl>";
$pgv_lang["mygedview_customize_help"]="Ao ver esta página pela primeira vez, já existiam blocos definidos. Caso deseje, você poderá personalizar o Portal do Site.<br /><br />Ao clicar este link, será exibida uma página onde será possível mover, excluir ou adicionar blocos ao Portal. Mais ajuda está disponível nesta página de personalização.";
$pgv_lang["mygedview_myjournal_help"]="Use este diário para suas anotações e lembretes.<br /><br />O conteúdo do Diário é privado e exclusivo de cada usuário.";
$pgv_lang["mygedview_login_help"]="Para obter acesso ao <b>\"Meu Portal\"</b>, você dever ser um usuário registrado no sistema.<br /><br />No \"Meu Portal\" você pode relacionar suas pessoas favoritas, enviar e receber mensagens, ver outros usuários conectados, etc ...<br /><br />Preencha os campos com seu nome de Usuário e Senha para obter acesso e se Conectar ao portal.";
$pgv_lang["desc_rootid_help"]="#pgv_lang[rootid_help]#";
$pgv_lang["desc_generations_help"]="#pgv_lang[PEDIGREE_GENERATIONS_help]#";
$pgv_lang["help_clippings.php"]="O Carrinho de Recortes permite que voce recorte dados e consolide tudo em um unico arquivo para \"download\". O arquivo de recortes é salvo no formato GEDCOM e pode ser importado por qualquer programa de Genealogia.<br /><ul><li>Como fazer recortes?<br />Em qualquer lugar onde houver um nome \"clicável\"  (pessoa, família ou fonte) acesse a página de Detalhes do nome. Nesta página você encontrará a opção <b>Adicionar ao Carrinho de Recortes em Outros</b>. Ao clicar esta opção, você verá várias opções para download.</li><br /><li>Como fazer o download?<br />Tão logo você tenha todos os recortes no carrinho, clique o link <b>Fazer Download agora</b> link e siga as instruções</li></ul>";
$pgv_lang["empty_lines_detected_help"]	= "PhpGedView encontrou linhas vazias no arquivo de entrada. Estas linhas podem causar êrros e serão removidas do arquivo antes de importa-lo.";
$pgv_lang["help_birthlist.xml"]		= "Este relatório lista as pessoas nascidas a uma certa hora ou lugar.";
$pgv_lang["help_contents_head_help"]="<b>CONTEÚDO DA AJUDA</b>";
$pgv_lang["help_contents_gedcom_info"]="Informação do GEDCOM";
$pgv_lang["help_contents_gedcom_places"]="Locais do GEDCOM";
$pgv_lang["admin_help_contents_head_help"]="<b>CONTEÚDO DA AJUDA<br /><br />ITENS DE AJUDA PARA  ADMINISTRATORES</b> adicionados ao ínicio da lista.";
$pgv_lang["ah2_help"]="_Configure o PhpGedView";
$pgv_lang["ah3_help"]="_GEDCOM: Adição vs Envio (upload)";
$pgv_lang["ah4_help"]="_GEDCOM: arquivo de Configuração";
$pgv_lang["ah5_help"]="_GEDCOM: Padrão";
$pgv_lang["ah6_help"]="_GEDCOM: Excluir";
$pgv_lang["ah7_help"]="_GEDCOM: Adicionar";
$pgv_lang["ah8_help"]="_GEDCOM: Criar Novo";
$pgv_lang["ah9_help"]="_GEDCOM: Descarregar (download)";
$pgv_lang["ah10_help"]="_GEDCOM: Página de Administração";
$pgv_lang["ah11_help"]="_GEDCOM: Configure";
$pgv_lang["ah12_help"]="_GEDCOM: Importar";
$pgv_lang["ah13_help"]="_GEDCOM: Carregar";
$pgv_lang["simple_filter_help"]		= "Filtro de pesquisa simples, baseado nos caracteres informados, onde curingas não são aceitos.";
$pgv_lang["show_thumb_help"]		= "Marque esta opção para exibir as Miniaturas.";
$pgv_lang["help_faq.php"]="A página de FAQs (Perguntas mais frequentes), contém uma lista de perguntas e respostas sobre o uso deste site genealógico.<br /><br />O Administrador do site é responsável pelo conteúdo e ordenação desta lista.";
$pgv_lang["text_faq_help"]="A página de FAQs (Perguntas mais frequentes), contém uma lista de perguntas e respostas sobre o uso deste site genealógico.<br /><br />O Administrador do site é responsável pelo conteúdo e ordenação desta lista.";
$pgv_lang["hs_title_help"]="<center>~Procurar texto de Ajuda~</center><br />É possível pesquisar a Ajuda do PhpGedView. O recurso Procurar texto de Ajuda proporciona um alto grau de controle sobre a forma a pesquisa funciona; você deveria ser capaz de encontrar o que procura facilmente.<br /><br />#pgv_lang[hs_keyword_advice]#<br /><br />#pgv_lang[hs_searchhow_advice]#<br /><br />#pgv_lang[hs_searchin_advice]#";
$pgv_lang["hs_intro"]="É possível pesquisar a Ajuda do PhpGedView. O recurso Procurar texto de Ajuda proporciona um alto grau de controle sobre a forma a pesquisa funciona; você deveria ser capaz de encontrar o que procura facilmente.";
?>