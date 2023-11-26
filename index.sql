SELECT users.id, users.name, groups.name AS name_group
FROM users
         JOIN groups ON users.group = groups.id
WHERE users.group = 1;