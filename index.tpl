<!DOCTYPE html>
    <html lang="ru">
        <head>
            <meta charset="utf-8">
            <title>Микро-шаблонизатор для PHP. Краткое описание.</title>            
        </head>
        <body>
            <p>
                Привет.
            </p>
            <p>
                Это еще один шаблонизатор для PHP
            </p>
            <p>
                Некоторые заказчики, которым нужно было привести в порядок старый код и добавить новую функциональность,
                резко возражали против использования сторонних шаблонизаторов. Переубедить их не удалось, пришлось делать свой.
            </p>
            <p>
                В сравнении с множеством прочих, этот шаблонизатор примитивен - состоит всего из двух классов и не имеет никаких
                расширенных возможностей, вроде собственного языка, переменных и прочего.
            </p>
            <p>
                Принцип работы прост - требовалось разделить старый код, в котором все было свалено в одну кучу, на html и php.
                Например:
            </p>
                    <pre>
                        &lt;html&gt;
                            &lt;body&gt;
                               Сегодня = &lt;?php echo date( 'd.m.Y' ); /&gt;
                            &lt;/body&gt;                            
                        &lt;/html&gt;
                    </pre>            
            <p>
                Соответственно, html-код переносим в файл шаблона, например, view.tpl:
            </p>
                
                    <pre>
                        &lt;html&gt;
                            &lt;body&gt;
                               Сегодня = %DATE%
                            &lt;/body&gt;                            
                        &lt;/html&gt;
                    </pre>                     
            <p>
                а код, вычисляющий дату, переносим в файл view.php, где только код php и никакого html:
                
                    <pre>
                        &lt;?php&gt;
                            require_once 'Template.class.php';
                            use template\Template;

                            $templateFileName = 'view.tpl';
                            $map = array(
                                '%DATE%' => date( 'd.m.Y' )
                            );
                            echo Template::toHtml( $templateFileName, $map );
                    </pre>                                     
            </p>
            <p>
                Теперь, при выполнении этого php-кода, текущая дата будет вычислена, помещена в массив $map,
                как значение ключа '%DATE%', из переменной $templateFileName будет взято имя файла шаблона, и
                в шаблоне все строки вида %DATE% буду заменены значением $map[ '%DATE%' ]. Сгенерированный html-код будет 
                отправлен пользователю с помощью echo.
            </p>
            <p>
                Разумеется, в массив $map можно добавить столько разных значений, сколько их должно быть на результирующей
                странице, например:
            </p>
            <pre>
                $map = array(
                    '%CELSIUS_TEMPERATURE%' => 36.6,
                    '%TIME%' => '23:11:45',
                    '%SELECT_ROW_COLOR%' => 'green'
                );
            </pre>
            <p>
                и тому подобное.
            </p>
            <p>
                Еще бывает нужно выводить на страницу не одиночные данные, а списки значений. Например, чтобы
                сформировать на странице раскрывающийся список, нужно не одно значение, а формируемые элементы однообразны.
                Отображать их по одиночке с помощю класса Template очень муторно (хотя и можно):
            </p>
            <pre>
                &lt;select&gt;
                    &lt;option&gt;23.56&lt;/option&gt;
                    &lt;option selected&gt;34.48&lt;/option&gt;
                    &lt;option&gt;40.06&lt;/option&gt;
                &lt;select&gt;
            </pre>
            <p>
                Для того, чтобы формировать подобные структуры, используем другой класс: TemplateWithList.
                Он аналогично классу Template может формировать результат по шаблону и одиночным данным, а также
                умеет работать со списком значений:
            </p>
            <pre>
                        &lt;?php&gt;
                            require_once 'TemplateWithList.class.php';
                            use template\TemplateWithList;

                            $templateFileName = 'view.tpl';
                            $map = array(
                                '%LIST%' => array(
                                    '%ITEM%' => 'item',
                                    '%MODE%' => 'mode',
                                    array(
                                        array( 'item' => 23.56, 'mode' => '' ),
                                        array( 'item' => 34.48, 'mode' => 'selected' ),
                                        array( 'item' => 40.06, 'mode' => '' ),
                                    )
                                )
                            );
                            echo TemplateWithList::toHtml( $templateFileName, $map );                
            </pre>
            <p>
                Шаблон для формирования структуры по списку будет таким:
            </p>
            <pre>
                &lt;select&gt;
                    %LIST%
                        &lt;option %MODE%&gt;%ITEM%&lt;/option&gt;
                    %LIST%
                &lt;select&gt;                
            </pre>
            <p>
                Здесь первое появление %LIST% обозначает начало шаблона для элемента списка, а второе появление %LIST% - 
                завершение этого шаблона. Строки вида '%ITEM%' => 'item' - связывают имя подстановки (%ITEM%) и имя ключа в списке из которого будут браться значения для этого имени. Для каждого значения вида array( 'item' => '23.56', 'mode' => '' ) из $map будет
                в результате создан текст по шаблону &lt;option %MODE%&gt;%ITEM%&lt;/option&gt;.
            </p>
            <p>
                В одном $map таких списков может быть несколько, одновременно можно задавать данные для одиночных значений.
            </p>
            <p>
                Если нужно, можно шаблон задавать не в отдельном файле, а прямо в строковой переменой. Для этого немного
                изменим вызов:
            </p>
            <pre>
                        &lt;?php&gt;
                            require_once 'Template.class.php';
                            use template\Template;

                            $templateFileName = '';
                            $map = array(
                                '%DATE%' => date( 'd.m.Y' )
                            );
                            $template = '&lt;div&gt;Сегодня = %DATE%&lt;/div&gt;';
                            echo Template::toHtml( $templateFileName, $map, $template );                
            </pre>
            <p>
                Если имя файла шаблона - пустая строка, то шаблонизатор решит, что шаблон нужно искать прямо в строковой переменной.
                Эту строковую переменную нужно дать третьим параметром при вызове Template::toHtml().
            </p>
            <p>
                Какой из классов - Template или TemplateWithList - лучше использовать в каких условиях? Я всегда использую
                TemplateWithList, поскольку он умеет обрабатывать и одиночные значения и списки.
            </p>
            <p>
                Поскольку Template и TemplateWithList классы, а современный PHP умеет перекрывать (override) статические
                методы,то при необходимости вы можете на базе этих классов создать свой со специфическим поведением.
            </p>
            <p>
                Если вы работаете с unit-тестами, то загляните в папку test - там лежат unit-тесты
                для обоих классов ( тесты написаны с использованием simpletest, сам simpletest тоже прилагается ).
            </p>
            <p>
                Если что-то неправильно работает - пишите.
            </p>
            <p>
                Ниже небольшой пример. Эта страница сама по себе - результат работы шаблонизатора, шаблон для нее
                находится в файле index.tpl, а значения, которые будут подставлены и отобразятся в следующих строках,
                заданы в файле index.php.
            <div style='margin-left: 50px;'>
                <div style="height:30px;">Подстановка одиночных значений:</div>
                <div style="height:70px;">
                    <table>
                        <tr>
                            <td>Сегодняшняя дата:</td><td>%DATE_NOW%</td>
                        </tr>
                        <tr>
                            <td>Время:</td><td> %TIME_NOW%</td>
                        </tr>
                    </table>
                </div>
                <div style="height:30px;">Подстановка списковых значений:</div>
                <select>
                    %SAMPLE_LIST%
                    <option %ITEM_MODE%>%SAMPLE_LIST_ITEM%</option>
                    %SAMPLE_LIST%
                </select>
            </div>
            </p>
        </body>
    </html>