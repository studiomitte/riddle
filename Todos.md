
## Todos:

- better icon

### API

- Endpoint für byId
- Key `datepublished` > müsste `datePublished` sein!
- Riddle fehlen:
   - Notizen
   - smaller thumb, e.g. width 250px
- Date: timezones?
- Statistik

https://webhook.site/#!/abef50cf-67f4-4e93-9e47-982ad693271f/6df83d29-9e37-4d8d-90d5-4dc4cec8333e/1

curl --insecure -X 'POST' 'https://jugendservice.at.local.studiomitte.build/riddle-endpoint' -H 'connection: close' -H 'expect: 100-continue' -H 'content-type: application/x-www-form-urlencoded' -H 'content-length: 1493' -H 'x-newrelic-transaction: PxQBVVcCCwdVUgVRBQYEUgUIFB8EBw8RVU4aVAwJVQEHAA8FAlMEUVBXDkNKQVsHBAEFV1ZVFTs=' -H 'x-newrelic-id: VQQHVVdUARABV1FVAgYAVw==' -H 'accept: */*' -H 'host: webhook.site' -d $'data=%7B%22riddleId%22%3A267360%2C%22data%22%3A%7B%22riddle%22%3A%7B%22id%22%3A267360%2C%22title%22%3A%22Kennst+du+dich+mit+Kinofilmen+aus%3F%3Cbr+%5C%2F%3E%22%2C%22type%22%3A%22quiz%22%7D%2C%22lead2%22%3A%7B%22Name%22%3A%7B%22value%22%3A%22hero%22%2C%22type%22%3A%22text%22%7D%2C%22Email%22%3A%7B%22value%22%3A%22hero%40gmail.com%22%2C%22type%22%3A%22email%22%7D%2C%22%23238694%3A+Text+block%22%3A%7B%22value%22%3A%22%3Cstrong%3EMach+mit+beim+Gewinnspiel%21%3C%5C%2Fstrong%3E%3Cbr+%5C%2F%3E%22%2C%22type%22%3A%22intro%22%7D%2C%22datenschutz%22%3A%7B%22value%22%3Atrue%2C%22type%22%3A%22checkbox%22%7D%7D%2C%22answers%22%3A%5B%7B%22question%22%3A%22Wie+hei%5Cu00dft+dieser+Film%3F%22%2C%22answer%22%3A%22Password+Swordfisch%22%2C%22correct%22%3Atrue%2C%22questionId%22%3A6%2C%22answerId%22%3A2%7D%2C%7B%22question%22%3A%22Wie+hei%5Cu00dft+der+Schauspieler+bei+Fluch+der+Karibik%22%2C%22answer%22%3A%22Johnny+Depp%22%2C%22correct%22%3Atrue%2C%22questionId%22%3A4%2C%22answerId%22%3A4%7D%5D%2C%22result%22%3A2%2C%22resultData%22%3A%7B%22scoreNumber%22%3A2%2C%22resultIndex%22%3A2%2C%22scorePercentage%22%3A100%2C%22scoreText%22%3A%22Your+score%3A+2%5C%2F2%22%2C%22title%22%3A%22Gut+gemacht%21%22%2C%22description%22%3A%22Das+war+beeindruckend%22%2C%22resultId%22%3A2%7D%2C%22embed%22%3A%7B%22parentLocation%22%3A%22https%3A%5C%2F%5C%2Fwww.riddle.com%5C%2Fshowcase%5C%2F267360%5C%2Fquiz%22%7D%2C%22timeTaken%22%3A%7B%22milliseconds%22%3A11469%2C%22formatted%22%3A%2200%3A00%3A11%3A469%22%7D%7D%7D'

curl --insecure -X 'POST' 'https://jugendservice.at.local.studiomitte.build/riddle-endpoint'  -H 'connection: close' -H 'expect: 100-continue' -H 'content-type: application/x-www-form-urlencoded' -H 'content-length: 1527' -H 'x-newrelic-transaction: PxRVVVZRClFSAFBQBwIGU1ADFB8EBw8RVU4aAAwIBgBRB11QA1EAU1ECBUNKQVsHBAEFV1ZVFTs=' -H 'x-newrelic-id: VQQHVVdUARABV1FVAgYAVw==' -H 'accept: */*' -H 'host: webhook.site' -d $'data=%7B%22riddleId%22%3A267360%2C%22data%22%3A%7B%22riddle%22%3A%7B%22id%22%3A267360%2C%22title%22%3A%22Kennst+du+dich+mit+Kinofilmen+aus%3F%3Cbr+%5C%2F%3E%22%2C%22type%22%3A%22quiz%22%7D%2C%22lead2%22%3A%7B%22Name%22%3A%7B%22value%22%3A%22max+mustermann%22%2C%22type%22%3A%22text%22%7D%2C%22Email%22%3A%7B%22value%22%3A%22fo%40bar.com%22%2C%22type%22%3A%22email%22%7D%2C%22%23238694%3A+Text+block%22%3A%7B%22value%22%3A%22%3Cstrong%3EMach+mit+beim+Gewinnspiel%21%3C%5C%2Fstrong%3E%3Cbr+%5C%2F%3E%22%2C%22type%22%3A%22intro%22%7D%2C%22datenschutz%22%3A%7B%22value%22%3Atrue%2C%22type%22%3A%22checkbox%22%7D%7D%2C%22answers%22%3A%5B%7B%22question%22%3A%22Wie+hei%5Cu00dft+dieser+Film%3F%22%2C%22answer%22%3A%22Ein+Fisch+namens+Wanda%22%2C%22correct%22%3Afalse%2C%22questionId%22%3A6%2C%22answerId%22%3A1%7D%2C%7B%22question%22%3A%22Wie+hei%5Cu00dft+der+Schauspieler+bei+Fluch+der+Karibik%22%2C%22answer%22%3A%22Johnny+Depp%22%2C%22correct%22%3Atrue%2C%22questionId%22%3A4%2C%22answerId%22%3A4%7D%5D%2C%22result%22%3A1%2C%22resultData%22%3A%7B%22scoreNumber%22%3A1%2C%22resultIndex%22%3A1%2C%22scorePercentage%22%3A50%2C%22scoreText%22%3A%22Your+score%3A+1%5C%2F2%22%2C%22title%22%3A%22Leider+nicht+alles+richtig%22%2C%22description%22%3A%22Das+war+leider+nicht+alles+richtig%22%2C%22resultId%22%3A1%7D%2C%22embed%22%3A%7B%22parentLocation%22%3A%22https%3A%5C%2F%5C%2Fwww.riddle.com%5C%2Fshowcase%5C%2F267360%5C%2Fquiz%22%7D%2C%22timeTaken%22%3A%7B%22milliseconds%22%3A10178%2C%22formatted%22%3A%2200%3A00%3A10%3A178%22%7D%7D%7D'


endpoint configurable
